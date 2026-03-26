<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DocumentExtraController extends Controller
{
    public function turnitin(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $scoreFilter = (string) $request->query('score', 'all');

        $query = DB::table('dokumen as d')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->leftJoin('master_divisi as md', 'd.id_divisi', '=', 'md.id_divisi')
            ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
            ->where('d.uploader_id', $user['id_user'])
            ->where('d.status_id', 5)
            ->select('d.*', 's.nama_status as status_name', 't.nama_tema', 'md.nama_divisi', 'u.username as uploader_name');

        if ($scoreFilter === 'none') {
            $query->where(function ($q) {
                $q->whereNull('d.turnitin')->orWhere('d.turnitin', 0);
            });
        } elseif ($scoreFilter === 'low') {
            $query->where('d.turnitin', '>', 0)->where('d.turnitin', '<=', 20);
        } elseif ($scoreFilter === 'medium') {
            $query->where('d.turnitin', '>', 20)->where('d.turnitin', '<=', 40);
        } elseif ($scoreFilter === 'high') {
            $query->where('d.turnitin', '>', 40);
        }

        $documents = $query->orderByDesc('d.tgl_unggah')->get();

        return view('turnitin', [
            'documents' => $documents,
            'score_filter' => $scoreFilter,
        ]);
    }

    public function exportTurnitin(Request $request)
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login');
        }

        $scoreFilter = (string) $request->query('score', 'all');

        $req = new Request(['score' => $scoreFilter]);
        $req->setLaravelSession($request->session());
        $view = $this->turnitin($req);
        if (!$view instanceof View) {
            return $view;
        }

        $docs = $view->getData()['documents'] ?? collect();

        $html = view('exports.turnitin_excel', [
            'documents' => $docs,
            'score_filter' => $scoreFilter,
        ])->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Turnitin_' . now()->format('Y-m-d_H-i-s') . '.xls"');
    }

    public function exportHistory(Request $request)
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login');
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

        $history = $query->orderByDesc('d.tgl_unggah')->get();

        $csvLines = [];
        $csvLines[] = 'Tanggal Upload,Waktu Upload,Judul Dokumen,Tema,Tahun,Status,Skor Turnitin,File Path';

        foreach ($history as $item) {
            $line = [
                optional($item->tgl_unggah ? now()->parse($item->tgl_unggah) : null)->format('d/m/Y') ?? '',
                optional($item->tgl_unggah ? now()->parse($item->tgl_unggah) : null)->format('H:i:s') ?? '',
                '"' . str_replace('"', '""', (string) ($item->judul ?? '')) . '"',
                '"' . str_replace('"', '""', (string) ($item->nama_tema ?? '')) . '"',
                '"' . str_replace('"', '""', (string) ($item->year_id ?? '')) . '"',
                '"' . str_replace('"', '""', (string) ($item->status_name ?? '')) . '"',
                (int) ($item->turnitin ?? 0) . '%',
                '"' . str_replace('"', '""', (string) ($item->file_path ?? '')) . '"',
            ];
            $csvLines[] = implode(',', $line);
        }

        return response(implode("\n", $csvLines))
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="riwayat_upload_' . now()->format('Y-m-d') . '.csv"');
    }

    public function documentDetail(Request $request, int $id): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login');
        }

        $doc = DB::table('dokumen as d')
            ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
            ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
            ->leftJoin('master_prodi as p', 'd.id_prodi', '=', 'p.id_prodi')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->where('d.dokumen_id', $id)
            ->select('d.*', 'u.username as uploader_name', 'u.email as uploader_email', 'j.nama_jurusan', 'p.nama_prodi', 't.nama_tema', 'y.tahun', 's.nama_status as status_name')
            ->first();

        if (!$doc) {
            return redirect()->route('browser.index')->withErrors(['detail_error' => 'Dokumen tidak ditemukan.']);
        }

        return view('document_detail', [
            'document' => $doc,
            'download_url' => asset('uploads/documents/' . basename((string) ($doc->file_path ?? ''))),
            'status_badge' => $this->mapStatusBadge((int) ($doc->status_id ?? 0)),
        ]);
    }

    public function downloadDocument(Request $request, int $id)
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            abort(403);
        }

        $doc = DB::table('dokumen')->where('dokumen_id', $id)->first();
        if (!$doc) {
            abort(404);
        }

        $isOwner = (int) $doc->uploader_id === (int) $user['id_user'];
        $isAdmin = in_array((string) ($user['role'] ?? ''), ['admin', 'superadmin', 'Admin', 'SuperAdmin'], true);
        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        $fileName = basename((string) ($doc->file_path ?? ''));
        $filePath = public_path('uploads/documents/' . $fileName);
        if (!is_file($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $fileName);
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
