<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        $searchQuery = trim((string) $request->query('q', ''));
        $results = collect();

        if ($searchQuery !== '') {
            $results = DB::table('dokumen as d')
                ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
                ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
                ->leftJoin('master_prodi as p', 'd.id_prodi', '=', 'p.id_prodi')
                ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
                ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
                ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
                ->where('d.status_id', 5)
                ->where(function ($query) use ($searchQuery) {
                    $query->where('d.judul', 'like', '%' . $searchQuery . '%')
                        ->orWhere('d.abstrak', 'like', '%' . $searchQuery . '%')
                        ->orWhere('d.kata_kunci', 'like', '%' . $searchQuery . '%');
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
                ])
                ->orderByDesc('d.tgl_unggah')
                ->get()
                ->map(fn ($doc) => $this->mapDocument($doc));

            DB::table('search_history')->insert([
                'user_id' => $user['id_user'],
                'keyword' => $searchQuery,
                'created_at' => now(),
            ]);

            $existing = DB::table('trending_keywords')->where('keyword', $searchQuery)->first();
            if ($existing) {
                DB::table('trending_keywords')
                    ->where('id', $existing->id)
                    ->update([
                        'search_count' => ((int) $existing->search_count) + 1,
                        'last_searched' => now(),
                    ]);
            } else {
                DB::table('trending_keywords')->insert([
                    'keyword' => $searchQuery,
                    'search_count' => 1,
                    'last_searched' => now(),
                ]);
            }
        }

        $popularKeywords = DB::table('trending_keywords')
            ->orderByDesc('search_count')
            ->orderByDesc('last_searched')
            ->limit(16)
            ->pluck('keyword')
            ->toArray();

        if (empty($popularKeywords)) {
            $popularKeywords = [
                'machine learning', 'data mining', 'artificial intelligence', 'deep learning',
                'neural network', 'big data', 'internet of things', 'cloud computing',
                'cybersecurity', 'blockchain', 'software engineering', 'database',
                'algorithm', 'computer vision', 'natural language processing', 'robotics',
            ];
        }

        return view('search', [
            'results' => $results,
            'search_query' => $searchQuery,
            'popular_keywords' => $popularKeywords,
        ]);
    }

    public function getDetail(Request $request): JsonResponse
    {
        if (!$this->sessionUser($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $documentId = (int) $request->query('id');

        $document = DB::table('dokumen as d')
            ->leftJoin('users as u', 'd.uploader_id', '=', 'u.id_user')
            ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
            ->leftJoin('master_prodi as p', 'd.id_prodi', '=', 'p.id_prodi')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->where('d.dokumen_id', $documentId)
            ->select([
                'd.*',
                'u.username as uploader_name',
                'u.email as uploader_email',
                'j.nama_jurusan',
                'p.nama_prodi',
                't.nama_tema',
                'y.tahun',
                's.nama_status as status_name',
            ])
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        return response()->json([
            'success' => true,
            'document' => $this->mapDocument($document),
        ]);
    }

    private function mapDocument(object $doc): array
    {
        $fileName = basename((string) ($doc->file_path ?? ''));
        $fileDiskPath = public_path('uploads/documents/' . $fileName);
        $keywords = !empty($doc->kata_kunci)
            ? array_values(array_filter(array_map('trim', explode(',', $doc->kata_kunci))))
            : [];

        return [
            'dokumen_id' => $doc->dokumen_id,
            'judul' => $doc->judul,
            'abstrak' => $doc->abstrak,
            'file_type' => strtolower(pathinfo($fileName, PATHINFO_EXTENSION)),
            'file_size' => is_file($fileDiskPath) ? filesize($fileDiskPath) : 0,
            'file_name' => $fileName,
            'download_url' => asset('uploads/documents/' . $fileName),
            'tgl_unggah' => $doc->tgl_unggah,
            'uploader_name' => $doc->uploader_name,
            'uploader_email' => $doc->uploader_email,
            'nama_jurusan' => $doc->nama_jurusan,
            'nama_prodi' => $doc->nama_prodi,
            'nama_tema' => $doc->nama_tema,
            'tahun' => $doc->tahun,
            'status_name' => $doc->status_name ?? 'Unknown',
            'status_badge' => $this->mapStatusBadge((int) ($doc->status_id ?? 0)),
            'status_id' => $doc->status_id,
            'turnitin' => $doc->turnitin,
            'turnitin_file' => $doc->turnitin_file,
            'kata_kunci' => $doc->kata_kunci,
            'keywords' => $keywords,
            'id_divisi' => $doc->id_divisi,
            'id_jurusan' => $doc->id_jurusan,
            'id_prodi' => $doc->id_prodi,
            'id_tema' => $doc->id_tema,
            'year_id' => $doc->year_id,
            'uploader_id' => $doc->uploader_id,
            'created_at' => $doc->tgl_unggah,
            'updated_at' => $doc->tgl_unggah,
        ];
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
