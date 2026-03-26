<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function deleteDocument(Request $request, int $id): RedirectResponse
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
            return back()->withErrors(['delete_error' => 'Dokumen tidak ditemukan atau tidak memiliki izin.']);
        }

        DB::beginTransaction();
        try {
            DB::table('document_screenings')->where('dokumen_id', $id)->delete();
            DB::table('dokumen')->where('dokumen_id', $id)->delete();

            $filePath = public_path('uploads/documents/' . basename((string) ($document->file_path ?? '')));
            if (is_file($filePath)) {
                @unlink($filePath);
            }

            $turnitinPath = public_path('uploads/turnitin/' . basename((string) ($document->turnitin_file ?? '')));
            if (!empty($document->turnitin_file) && is_file($turnitinPath)) {
                @unlink($turnitinPath);
            }

            DB::commit();
            return redirect()->route('documents.my')->with('delete_success', true);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['delete_error' => 'Gagal menghapus dokumen: ' . $e->getMessage()]);
        }
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
