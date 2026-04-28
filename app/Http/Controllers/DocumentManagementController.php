<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class DocumentManagementController extends Controller
{
    public function myDocuments(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $documents = DB::table('dokumen as d')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->where('d.uploader_id', $user['id_user'])
            ->select('d.*', 's.nama_status as status_name', 't.nama_tema')
            ->orderByDesc('d.tgl_unggah')
            ->get()
            ->map(function ($doc) {
                $doc->status_badge = $this->mapStatusBadge((int) ($doc->status_id ?? 0));
                $doc->download_url = asset('uploads/documents/' . basename((string) ($doc->file_path ?? '')));
                return $doc;
            });

        return view('my_documents', [
            'my_documents' => $documents,
        ]);
    }

    public function uploadHistory(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $dateFilter = (string) $request->query('date', 'all');

        $query = DB::table('dokumen as d')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->where('d.uploader_id', $user['id_user'])
            ->select('d.*', 's.nama_status as status_name', 't.nama_tema');

        if ($dateFilter === 'today') {
            $query->whereDate('d.tgl_unggah', now()->toDateString());
        } elseif ($dateFilter === 'week') {
            $query->where('d.tgl_unggah', '>=', now()->subDays(7));
        } elseif ($dateFilter === 'month') {
            $query->where('d.tgl_unggah', '>=', now()->subDays(30));
        }

        $history = $query
            ->orderByDesc('d.tgl_unggah')
            ->get()
            ->map(function ($item) {
                $item->status_badge = $this->mapStatusBadge((int) ($item->status_id ?? 0));
                $item->download_url = asset('uploads/documents/' . basename((string) ($item->file_path ?? '')));
                return $item;
            });

        return view('upload_history', [
            'history' => $history,
            'date_filter' => $dateFilter,
        ]);
    }

    public function edit(Request $request, int $id): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $document = DB::table('dokumen')
            ->where('dokumen_id', $id)
            ->where('uploader_id', $user['id_user'])
            ->first();

        if (!$document) {
            return redirect()->route('documents.my')->withErrors(['edit_error' => 'Dokumen tidak ditemukan.']);
        }

        $divisi = DB::table('master_divisi')->orderBy('nama_divisi')->get();
        $jurusan = DB::table('master_jurusan')->orderBy('nama_jurusan')->get();
        $prodi = DB::table('master_prodi')->orderBy('nama_prodi')->get();
        $tema = DB::table('master_tema')->orderBy('nama_tema')->get();
        $tahun = DB::table('master_tahun')->orderByDesc('tahun')->get();

        return view('document_edit', [
            'document' => $document,
            'divisi_data' => $divisi,
            'jurusan_data' => $jurusan,
            'prodi_data' => $prodi,
            'tema_data' => $tema,
            'tahun_data' => $tahun,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $document = DB::table('dokumen')
            ->where('dokumen_id', $id)
            ->where('uploader_id', $user['id_user'])
            ->first();

        if (!$document) {
            return redirect()->route('documents.my')->withErrors(['edit_error' => 'Dokumen tidak ditemukan.']);
        }

        $editableStatuses = [1, 4];
        if (!in_array((int) ($document->status_id ?? 0), $editableStatuses, true)) {
            return back()->withErrors(['edit_error' => 'Dokumen hanya bisa diedit saat status menunggu review atau ditolak.']);
        }

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'abstrak' => ['nullable', 'string'],
            'kata_kunci' => ['nullable', 'string', 'max:255'],
            'id_divisi' => ['required', 'integer', 'exists:master_divisi,id_divisi'],
            'id_jurusan' => ['required', 'integer', 'exists:master_jurusan,id_jurusan'],
            'id_prodi' => ['required', 'integer', 'exists:master_prodi,id_prodi'],
            'id_tema' => ['required', 'integer', 'exists:master_tema,id_tema'],
            'year_id' => ['required', 'integer', 'exists:master_tahun,year_id'],
            'turnitin' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::table('dokumen')
            ->where('dokumen_id', $id)
            ->update([
                'judul' => $validated['judul'],
                'abstrak' => $validated['abstrak'] ?? null,
                'kata_kunci' => $validated['kata_kunci'] ?? null,
                'id_divisi' => $validated['id_divisi'],
                'id_jurusan' => $validated['id_jurusan'],
                'id_prodi' => $validated['id_prodi'],
                'id_tema' => $validated['id_tema'],
                'year_id' => $validated['year_id'],
                'turnitin' => (int) round((float) ($validated['turnitin'] ?? 0)),
            ]);

        return redirect()->route('documents.my')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $document = DB::table('dokumen')
            ->where('dokumen_id', $id)
            ->where('uploader_id', $user['id_user'])
            ->first();

        if (!$document) {
            return redirect()->route('documents.my')->withErrors(['delete_error' => 'Dokumen tidak ditemukan.']);
        }

        $editableStatuses = [1, 4];
        if (!in_array((int) ($document->status_id ?? 0), $editableStatuses, true)) {
            return back()->withErrors(['delete_error' => 'Dokumen hanya bisa dihapus saat status menunggu review atau ditolak.']);
        }

        if (!empty($document->file_path)) {
            $filePath = public_path('uploads/documents/' . $document->file_path);
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }

        if (!empty($document->turnitin_file)) {
            $turnitinPath = public_path('uploads/turnitin/' . $document->turnitin_file);
            if (is_file($turnitinPath)) {
                @unlink($turnitinPath);
            }
        }

        DB::table('dokumen')->where('dokumen_id', $id)->delete();

        return redirect()->route('documents.my')->with('success', 'Dokumen berhasil dihapus.');
    }

    private function mapStatusBadge(int $statusId): string
    {
        return match ($statusId) {
            5 => 'badge-success',
            4 => 'badge-danger',
            3 => 'badge-secondary',
            2 => 'badge-warning',
            default => 'badge-info',
        };
    }

    private function sessionUser(Request $request): ?array
    {
        $sessionUser = $request->session()->get('auth_user');
        if (!$sessionUser || empty($sessionUser['id_user'])) {
            return null;
        }

        return [
            'id_user' => (int) $sessionUser['id_user'],
            'role' => $sessionUser['role'] ?? 'mahasiswa',
        ];
    }
}
