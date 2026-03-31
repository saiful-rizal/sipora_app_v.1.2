<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sessionUser = $request->session()->get('auth_user');
        if (!$sessionUser || empty($sessionUser['id_user'])) {
            return redirect()->route('login');
        }

        $user = $sessionUser ? (object) [
            'id_user' => $sessionUser['id_user'] ?? null,
            'username' => $sessionUser['username'] ?? 'Guest',
            'nama_lengkap' => $sessionUser['nama_lengkap'] ?? ($sessionUser['username'] ?? 'Guest'),
            'email' => $sessionUser['email'] ?? null,
            'role' => $sessionUser['role'] ?? 'guest',
        ] : null;

        $userId = (int) ($sessionUser['id_user'] ?? 0);
        $userRole = (string) ($sessionUser['role'] ?? 'guest');
        $isAdmin = in_array($userRole, ['superadmin', 'admin', '1', 'Admin', 'SuperAdmin'], true);

        $documentsQuery = DB::table('dokumen')
            ->leftJoin('users as u', 'dokumen.uploader_id', '=', 'u.id_user')
            ->leftJoin('master_jurusan as mj', 'dokumen.id_jurusan', '=', 'mj.id_jurusan')
            ->leftJoin('master_prodi as mp', 'dokumen.id_prodi', '=', 'mp.id_prodi')
            ->leftJoin('master_tema as mt', 'dokumen.id_tema', '=', 'mt.id_tema')
            ->leftJoin('master_tahun as my', 'dokumen.year_id', '=', 'my.year_id')
            ->leftJoin('master_status_dokumen as msd', 'dokumen.status_id', '=', 'msd.status_id')
            ->when(!$isAdmin, function ($query) use ($userId) {
                $query->where('dokumen.uploader_id', $userId);
            })
            ->where('dokumen.status_id', 5)
            ->select([
                'dokumen.*',
                'u.username as uploader_name',
                'u.email as uploader_email',
                'mj.nama_jurusan',
                'mp.nama_prodi',
                'mt.nama_tema',
                'my.tahun',
                'msd.nama_status as status_name',
            ])
            ->orderByDesc('dokumen.tgl_unggah');

        $documents = $documentsQuery->get()->map(function ($doc) {
                $mapped = (array) $doc;
            $filePath = (string) ($doc->file_path ?? '');
            $fileName = basename($filePath);
            $filePublicPath = public_path('uploads/documents/' . $fileName);
            $mapped['file_size'] = is_file($filePublicPath) ? filesize($filePublicPath) : 0;
                $mapped['status_badge'] = $this->mapStatusBadge((int) ($doc->status_id ?? 0));
                $mapped['status_name'] = $mapped['status_name'] ?: $this->mapStatusName((int) ($doc->status_id ?? 0));
                return $mapped;
            });

        $totalDokumen = $documents->count();

        $uploadBaru = DB::table('dokumen')
            ->when(!$isAdmin, function ($query) use ($userId) {
                $query->where('uploader_id', $userId);
            })
            ->where('tgl_unggah', '>=', now()->startOfMonth())
            ->count();

        $persentasePenggunaan = $totalDokumen > 0 ? round(($uploadBaru / $totalDokumen) * 100, 1) : 0;

        $topReadDocuments = DB::table('dokumen as d')
            ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->where('d.status_id', 5)
            ->orderByDesc('d.view_count')
            ->orderByDesc('d.tgl_unggah')
            ->limit(10)
            ->select([
                'd.dokumen_id',
                'd.judul',
                'd.view_count',
                'd.jenis_dokumen',
                'j.nama_jurusan',
                'y.tahun',
            ])
            ->get();

        return view('dashboard', [
            'documents' => $documents,
            'totalDokumen' => $totalDokumen,
            'uploadBaru' => $uploadBaru,
            'persentasePenggunaan' => $persentasePenggunaan,
            'topReadDocuments' => $topReadDocuments,
            'user' => $user,
        ]);
    }

    public function getDetail(Request $request)
    {
        $sessionUser = $request->session()->get('auth_user');
        if (!$sessionUser || empty($sessionUser['id_user'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userId = (int) ($sessionUser['id_user'] ?? 0);
        $userRole = (string) ($sessionUser['role'] ?? 'guest');
        $isAdmin = in_array($userRole, ['superadmin', 'admin', '1', 'Admin', 'SuperAdmin'], true);

        $documentId = $request->input('id');
        $documentQuery = DB::table('dokumen as d')
            ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
            ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
            ->leftJoin('master_prodi as p', 'd.id_prodi', '=', 'p.id_prodi')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->where('d.dokumen_id', $documentId)
            ->when(!$isAdmin, function ($query) use ($userId) {
                $query->where('d.uploader_id', $userId);
            })
            ->select([
                'd.*',
                'u.username as uploader_name',
                'u.email as uploader_email',
                'j.nama_jurusan',
                'p.nama_prodi',
                't.nama_tema',
                'y.tahun',
                's.nama_status as status_name',
            ]);

        $document = $documentQuery->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        DB::table('dokumen')
            ->where('dokumen_id', $document->dokumen_id)
            ->increment('view_count');

        $document->view_count = (int) ($document->view_count ?? 0) + 1;

        $filePath = (string) ($document->file_path ?? '');
        $fileName = basename($filePath);
        $filePublicPath = public_path('uploads/documents/' . $fileName);
        $fileURL = asset('uploads/documents/' . $fileName);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = is_file($filePublicPath) ? filesize($filePublicPath) : 0;
        $keywords = !empty($document->kata_kunci)
            ? array_values(array_filter(array_map('trim', explode(',', $document->kata_kunci))))
            : [];

        $response = [
            'success' => true,
            'document' => [
                'dokumen_id' => $document->dokumen_id,
                'judul' => $document->judul,
                'abstrak' => $document->abstrak,
                'file_type' => $fileExt,
                'file_size' => $fileSize,
                'file_name' => $fileName,
                'download_url' => $fileURL,
                'tgl_unggah' => $document->tgl_unggah,
                'uploader_name' => $document->uploader_name ?? 'Admin',
                'uploader_email' => $document->uploader_email ?? '',
                'nama_jurusan' => $document->nama_jurusan ?? '',
                'nama_prodi' => $document->nama_prodi ?? '',
                'nama_tema' => $document->nama_tema ?? '',
                'tahun' => $document->tahun ?? '',
                'status_name' => $document->status_name ?: $this->mapStatusName((int) ($document->status_id ?? 0)),
                'status_badge' => $this->mapStatusBadge((int) ($document->status_id ?? 0)),
                'turnitin' => $document->turnitin,
                'turnitin_file' => $document->turnitin_file,
                'view_count' => $document->view_count,
                'jenis_dokumen' => $document->jenis_dokumen,
                'kata_kunci' => $document->kata_kunci,
                'keywords' => $keywords,
                'id_divisi' => $document->id_divisi,
                'can_edit' => $isAdmin || ((int) ($document->uploader_id ?? 0) === $userId),
                'created_at' => $document->tgl_unggah,
                'updated_at' => $document->tgl_unggah,
                'id_jurusan' => $document->id_jurusan,
                'id_prodi' => $document->id_prodi,
                'id_tema' => $document->id_tema,
                'year_id' => $document->year_id,
                'uploader_id' => $document->uploader_id,
            ]
        ];

        return response()->json($response);
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

    private function mapStatusName(int $statusId): string
    {
        return match ($statusId) {
            1 => 'Menunggu Persetujuan',
            2 => 'Sedang Direview',
            3 => 'Menunggu Publikasi',
            4 => 'Ditolak',
            5 => 'Diterbitkan',
            default => 'Unknown',
        };
    }
}
