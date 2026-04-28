<?php

namespace App\Http\Controllers;

use App\Services\DocumentScreeningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UploadController extends Controller
{
    private const SCREENING_SESSION_KEY = 'upload_screening_approval';

    public function __construct(private readonly DocumentScreeningService $screeningService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $divisi = DB::table('master_divisi')->orderBy('nama_divisi')->get();
        $jurusan = DB::table('master_jurusan')->orderBy('nama_jurusan')->get();
        $prodi = DB::table('master_prodi')->orderBy('nama_prodi')->get();
        $tema = DB::table('master_tema')->orderBy('nama_tema')->get();
        $tahun = DB::table('master_tahun')->orderByDesc('tahun')->get();

        $myDocuments = DB::table('dokumen as d')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->where('d.uploader_id', $user['id_user'])
            ->select('d.*', 's.nama_status')
            ->orderByDesc('d.tgl_unggah')
            ->limit(20)
            ->get();

        return view('upload', [
            'divisi_data' => $divisi,
            'jurusan_data' => $jurusan,
            'prodi_data' => $prodi,
            'tema_data' => $tema,
            'tahun_data' => $tahun,
            'my_documents' => $myDocuments,
            'upload_success' => (bool) session('upload_success', false),
            'screening_result' => session('screening_result'),
        ]);
    }

    public function getProdi(Request $request): JsonResponse
    {
        $idJurusan = (int) $request->query('id_jurusan', 0);

        $data = DB::table('master_prodi')
            ->where('id_jurusan', $idJurusan)
            ->orderBy('nama_prodi')
            ->select('id_prodi', 'nama_prodi')
            ->get();

        return response()->json($data);
    }

    public function preScreen(Request $request): JsonResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login tidak valid. Silakan login ulang.',
            ], 401);
        }

        $validated = $request->validate([
            'file_dokumen' => ['required', 'file', 'mimes:pdf,docx', 'max:10240'],
        ], [
            'file_dokumen.required' => 'Silakan pilih file dokumen terlebih dahulu.',
            'file_dokumen.max' => 'Ukuran file dokumen maksimal 10MB.',
        ]);

        $tmpDir = storage_path('app/screening-temp');
        File::ensureDirectoryExists($tmpDir);

        $tmpFilePath = null;
        try {
            $uploaded = $validated['file_dokumen'];
            $tmpFileName = $user['id_user'] . '_prescreen_' . uniqid('', true) . '.' . $uploaded->getClientOriginalExtension();
            $uploaded->move($tmpDir, $tmpFileName);
            $tmpFilePath = $tmpDir . DIRECTORY_SEPARATOR . $tmpFileName;

            $fileHash = hash_file('sha256', $tmpFilePath) ?: '';
            $screeningResult = $this->screeningService->analyze($tmpFilePath);

            if (!empty($screeningResult['passed']) && $fileHash !== '') {
                $request->session()->put(self::SCREENING_SESSION_KEY, [
                    'passed' => true,
                    'file_hash' => $fileHash,
                    'result' => $screeningResult,
                    'verified_at' => now()->timestamp,
                ]);
            } else {
                $request->session()->forget(self::SCREENING_SESSION_KEY);
            }

            return response()->json([
                'success' => true,
                'passed' => (bool) ($screeningResult['passed'] ?? false),
                'score' => (int) ($screeningResult['score'] ?? 0),
                'file_hash' => $fileHash,
                'screening_result' => $screeningResult,
                'message' => $screeningResult['message'] ?? 'Screening selesai.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan screening: ' . $e->getMessage(),
            ], 500);
        } finally {
            if ($tmpFilePath && is_file($tmpFilePath)) {
                @unlink($tmpFilePath);
            }
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'abstrak' => ['required', 'string'],
            'kata_kunci' => ['required', 'string', 'max:255'],
            'id_divisi' => ['required', 'integer', 'exists:master_divisi,id_divisi'],
            'id_jurusan' => ['required', 'integer', 'exists:master_jurusan,id_jurusan'],
            'id_prodi' => ['required', 'integer', 'exists:master_prodi,id_prodi'],
            'id_tema' => ['required', 'integer', 'exists:master_tema,id_tema'],
            'year_id' => ['required', 'integer', 'exists:master_tahun,year_id'],
            'status_id' => ['nullable', 'integer', 'exists:master_status_dokumen,status_id'],
            'turnitin' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'file_dokumen' => ['required', 'file', 'mimes:pdf,docx', 'max:10240'],
            'screening_file_hash' => ['required', 'string'],
            'turnitin_file' => ['nullable', 'file', 'mimes:pdf,docx', 'max:5120'],
        ], [
            'file_dokumen.max' => 'Ukuran file dokumen maksimal 10MB.',
            'screening_file_hash.required' => 'Dokumen wajib lolos screening sebelum upload.',
            'turnitin_file.max' => 'Ukuran file Turnitin maksimal 5MB.',
        ]);

        $approval = $request->session()->get(self::SCREENING_SESSION_KEY);
        $incomingHash = (string) ($validated['screening_file_hash'] ?? '');
        $currentFileHash = hash_file('sha256', $request->file('file_dokumen')->getRealPath()) ?: '';

        $isApproved = is_array($approval)
            && !empty($approval['passed'])
            && !empty($approval['file_hash'])
            && hash_equals((string) $approval['file_hash'], $incomingHash)
            && hash_equals((string) $approval['file_hash'], $currentFileHash);

        if (!$isApproved) {
            return back()
                ->withInput()
                ->withErrors(['upload_error' => 'File belum lolos screening atau file berubah. Silakan lakukan screening ulang.']);
        }

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

            $screeningResult = is_array($approval['result'] ?? null)
                ? $approval['result']
                : $this->screeningService->analyze($documentsDir . DIRECTORY_SEPARATOR . $documentFileName);

            if ($request->hasFile('turnitin_file')) {
                $turnitinFile = $request->file('turnitin_file');
                $turnitinFileName = $user['id_user'] . '_turnitin_' . time() . '.' . $turnitinFile->getClientOriginalExtension();
                $turnitinFile->move($turnitinDir, $turnitinFileName);
            }

            $docId = DB::table('dokumen')->insertGetId([
                'judul' => $validated['judul'],
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

            $yoloResult = $screeningResult['yolo_ocr'] ?? null;
            if (is_array($yoloResult) && Schema::hasTable('document_yolo_screenings')) {
                DB::table('document_yolo_screenings')->insert([
                    'dokumen_id' => $docId,
                    'model_used' => (string) ($yoloResult['model_used'] ?? config('document_screening.model', 'yolov8n.pt')),
                    'required_classes' => (string) ($yoloResult['required_classes'] ?? config('document_screening.required_classes', 'heading,title,paragraph')),
                    'ocr_lang' => (string) ($yoloResult['ocr_lang'] ?? config('document_screening.ocr_lang', 'eng+ind')),
                    'detected_classes' => json_encode($yoloResult['detected_classes'] ?? [], JSON_UNESCAPED_UNICODE),
                    'format_passed' => (bool) ($yoloResult['format_passed'] ?? false),
                    'pages_count' => (int) ($yoloResult['pages_count'] ?? 0),
                    'ocr_text_sample' => (string) ($yoloResult['ocr_text_sample'] ?? ''),
                    'result_json' => (string) ($yoloResult['result_json'] ?? json_encode($yoloResult, JSON_UNESCAPED_UNICODE)),
                    'result_file' => (string) ($yoloResult['result_file'] ?? ''),
                    'created_at' => now(),
                ]);
            }

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
            $request->session()->forget(self::SCREENING_SESSION_KEY);

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
}
