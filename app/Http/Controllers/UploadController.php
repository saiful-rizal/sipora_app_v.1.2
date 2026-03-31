<?php

namespace App\Http\Controllers;

use App\Services\DocumentScreeningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function __construct(private readonly DocumentScreeningService $screeningService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $divisi = $this->getDivisiData();
        $jurusan = $this->getJurusanData();
        $prodi = $this->getProdiData();
        $tema = $this->getTemaData();
        $tahun = DB::table('master_tahun')->orderByDesc('tahun')->get();

        $myDocuments = DB::table('dokumen as d')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->where('d.uploader_id', $user['id_user'])
            ->select('d.*', 's.nama_status')
            ->orderByDesc('d.tgl_unggah')
            ->limit(20)
            ->get();

        $lastUploadedDocument = DB::table('dokumen')
            ->where('uploader_id', $user['id_user'])
            ->orderByDesc('tgl_unggah')
            ->first();

        return view('upload', [
            'divisi_data' => $divisi,
            'jurusan_data' => $jurusan,
            'prodi_data' => $prodi,
            'tema_data' => $tema,
            'tahun_data' => $tahun,
            'my_documents' => $myDocuments,
            'last_uploaded_document' => $lastUploadedDocument,
            'upload_success' => (bool) session('upload_success', false),
            'screening_result' => session('screening_result'),
        ]);
    }

    public function getProdi(Request $request): JsonResponse
    {
        $idJurusan = (int) $request->query('id_jurusan', 0);

        if (Schema::hasTable('master_prodi')) {
            $data = DB::table('master_prodi')
                ->where('id_jurusan', $idJurusan)
                ->orderBy('nama_prodi')
                ->select('id_prodi', 'nama_prodi')
                ->get();
        } elseif (Schema::hasTable('prodi')) {
            $data = DB::table('prodi')
                ->where('id_jurusan', $idJurusan)
                ->orderBy('nama_prodi')
                ->select('id_prodi', 'nama_prodi')
                ->get();
        } else {
            $data = collect();
        }

        return response()->json($data);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        [$divisiTable, $divisiKey] = $this->validationTargetForDivisi();
        [$jurusanTable, $jurusanKey] = $this->validationTargetForJurusan();
        [$prodiTable, $prodiKey] = $this->validationTargetForProdi();
        [$temaTable, $temaKey] = $this->validationTargetForTema();

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'jenis_dokumen' => ['required', 'in:laporan_magang,tugas_akhir,skripsi,tesis'],
            'abstrak' => ['required', 'string'],
            'kata_kunci' => ['required', 'string', 'max:255'],
            'id_divisi' => ['required', 'integer', 'exists:' . $divisiTable . ',' . $divisiKey],
            'id_jurusan' => ['required', 'integer', 'exists:' . $jurusanTable . ',' . $jurusanKey],
            'id_prodi' => ['required', 'integer', 'exists:' . $prodiTable . ',' . $prodiKey],
            'id_tema' => ['required', 'integer', 'exists:' . $temaTable . ',' . $temaKey],
            'year_id' => ['required', 'integer', 'exists:master_tahun,year_id'],
            'status_id' => ['nullable', 'integer', 'exists:master_status_dokumen,status_id'],
            'turnitin' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'file_dokumen' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx', 'max:10240'],
            'turnitin_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ], [
            'file_dokumen.max' => 'Ukuran file dokumen maksimal 10MB.',
            'turnitin_file.max' => 'Ukuran file Turnitin maksimal 5MB.',
        ]);

        $documentsDir = public_path('uploads/documents');
        $turnitinDir = public_path('uploads/turnitin');
        File::ensureDirectoryExists($documentsDir);
        File::ensureDirectoryExists($turnitinDir);

        $documentFileName = null;
        $turnitinFileName = null;
        $screeningResult = null;

        DB::beginTransaction();
        try {
            $mainFile = $request->file('file_dokumen');
            $documentFileName = $user['id_user'] . '_' . time() . '.' . $mainFile->getClientOriginalExtension();
            $mainFile->move($documentsDir, $documentFileName);

            $screeningResult = $this->screeningService->analyze($documentsDir . DIRECTORY_SEPARATOR . $documentFileName);

            if ($request->hasFile('turnitin_file')) {
                $turnitinFile = $request->file('turnitin_file');
                $turnitinFileName = $user['id_user'] . '_turnitin_' . time() . '.' . $turnitinFile->getClientOriginalExtension();
                $turnitinFile->move($turnitinDir, $turnitinFileName);
            }

            $docId = DB::table('dokumen')->insertGetId([
                'judul' => $validated['judul'],
                'jenis_dokumen' => $validated['jenis_dokumen'],
                'abstrak' => $validated['abstrak'],
                'kata_kunci' => $validated['kata_kunci'],
                'id_divisi' => $validated['id_divisi'],
                'id_jurusan' => $validated['id_jurusan'],
                'id_prodi' => $validated['id_prodi'],
                'id_tema' => $validated['id_tema'],
                'year_id' => $validated['year_id'],
                'file_path' => $documentFileName,
                'turnitin_file' => $turnitinFileName,
                'uploader_id' => $user['id_user'],
                'status_id' => (int) ($validated['status_id'] ?? 1),
                'turnitin' => (int) round((float) ($validated['turnitin'] ?? 0)),
                'tgl_unggah' => now(),
            ]);

            DB::table('document_screenings')->insert([
                'dokumen_id' => $docId,
                'passed' => (bool) ($screeningResult['passed'] ?? false),
                'score' => (int) ($screeningResult['score'] ?? 0),
                'checks_json' => json_encode($screeningResult['checks'] ?? [], JSON_UNESCAPED_UNICODE),
                'message' => $screeningResult['message'] ?? null,
                'created_at' => now(),
            ]);

            DB::table('notifications')->insert([
                [
                    'user_id' => null,
                    'actor_id' => $user['id_user'],
                    'doc_id' => $docId,
                    'type' => 'upload',
                    'title' => 'Dokumen Baru',
                    'message' => '<strong>' . e($user['username']) . '</strong> mengunggah dokumen: "' . e($validated['judul']) . '"',
                    'icon_type' => 'info',
                    'icon_class' => 'bi-file-earmark-plus',
                    'is_read' => 0,
                    'created_at' => now(),
                ],
                [
                    'user_id' => $user['id_user'],
                    'actor_id' => $user['id_user'],
                    'doc_id' => $docId,
                    'type' => 'upload_confirm',
                    'title' => 'Upload Berhasil',
                    'message' => 'Dokumen "' . e($validated['judul']) . '" berhasil diunggah.',
                    'icon_type' => 'success',
                    'icon_class' => 'bi-check-circle-fill',
                    'is_read' => 0,
                    'created_at' => now(),
                ],
            ]);

            DB::commit();

            return redirect()
                ->route('upload.index')
                ->with('upload_success', true)
                ->with('screening_result', $screeningResult);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($documentFileName && is_file($documentsDir . DIRECTORY_SEPARATOR . $documentFileName)) {
                @unlink($documentsDir . DIRECTORY_SEPARATOR . $documentFileName);
            }
            if ($turnitinFileName && is_file($turnitinDir . DIRECTORY_SEPARATOR . $turnitinFileName)) {
                @unlink($turnitinDir . DIRECTORY_SEPARATOR . $turnitinFileName);
            }

            return back()
                ->withInput()
                ->withErrors(['upload_error' => 'Gagal menyimpan dokumen: ' . $e->getMessage()]);
        }
    }

    private function sessionUser(Request $request): ?array
    {
        $sessionUser = $request->session()->get('auth_user');
        if (!$sessionUser || empty($sessionUser['id_user'])) {
            return null;
        }

        return [
            'id_user' => (int) $sessionUser['id_user'],
            'username' => $sessionUser['username'] ?? 'User',
            'email' => $sessionUser['email'] ?? '',
            'role' => $sessionUser['role'] ?? 'mahasiswa',
        ];
    }

    private function getJurusanData(): Collection
    {
        if (Schema::hasTable('master_jurusan')) {
            return DB::table('master_jurusan')->orderBy('nama_jurusan')->get();
        }

        if (Schema::hasTable('jurusan')) {
            return DB::table('jurusan')
                ->select('id_jurusan', 'nama_jurusan')
                ->orderBy('nama_jurusan')
                ->get();
        }

        return collect();
    }

    private function getProdiData(): Collection
    {
        if (Schema::hasTable('master_prodi')) {
            return DB::table('master_prodi')->orderBy('nama_prodi')->get();
        }

        if (Schema::hasTable('prodi')) {
            return DB::table('prodi')
                ->select('id_prodi', 'id_jurusan', 'nama_prodi')
                ->orderBy('nama_prodi')
                ->get();
        }

        return collect();
    }

    private function getTemaData(): Collection
    {
        if (Schema::hasTable('master_tema')) {
            return DB::table('master_tema')->orderBy('nama_tema')->get();
        }

        if (Schema::hasTable('kategori_perpustakaan')) {
            return DB::table('kategori_perpustakaan')
                ->selectRaw('id_kategori as id_tema, nama_kategori as nama_tema')
                ->orderBy('nama_kategori')
                ->get();
        }

        return collect();
    }

    private function getDivisiData(): Collection
    {
        if (Schema::hasTable('master_divisi')) {
            return DB::table('master_divisi')->orderBy('nama_divisi')->get();
        }

        if (Schema::hasTable('kategori_perpustakaan')) {
            return DB::table('kategori_perpustakaan')
                ->selectRaw('id_kategori as id_divisi, nama_kategori as nama_divisi')
                ->orderBy('nama_kategori')
                ->get();
        }

        return collect();
    }

    private function validationTargetForDivisi(): array
    {
        if (Schema::hasTable('master_divisi')) {
            return ['master_divisi', 'id_divisi'];
        }

        if (Schema::hasTable('kategori_perpustakaan')) {
            return ['kategori_perpustakaan', 'id_kategori'];
        }

        return ['master_divisi', 'id_divisi'];
    }

    private function validationTargetForJurusan(): array
    {
        if (Schema::hasTable('master_jurusan')) {
            return ['master_jurusan', 'id_jurusan'];
        }

        if (Schema::hasTable('jurusan')) {
            return ['jurusan', 'id_jurusan'];
        }

        return ['master_jurusan', 'id_jurusan'];
    }

    private function validationTargetForProdi(): array
    {
        if (Schema::hasTable('master_prodi')) {
            return ['master_prodi', 'id_prodi'];
        }

        if (Schema::hasTable('prodi')) {
            return ['prodi', 'id_prodi'];
        }

        return ['master_prodi', 'id_prodi'];
    }

    private function validationTargetForTema(): array
    {
        if (Schema::hasTable('master_tema')) {
            return ['master_tema', 'id_tema'];
        }

        if (Schema::hasTable('kategori_perpustakaan')) {
            return ['kategori_perpustakaan', 'id_kategori'];
        }

        return ['master_tema', 'id_tema'];
    }
}
