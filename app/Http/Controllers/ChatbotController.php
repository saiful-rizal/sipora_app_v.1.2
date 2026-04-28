<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $text = trim($validated['message']);
        $reply = $this->buildReply($request, $text);

        return response()->json([
            'success' => true,
            'reply_html' => $reply['html'],
            'reply_text' => $reply['text'],
        ]);
    }

    private function buildReply(Request $request, string $message): array
    {
        $lower = mb_strtolower($message);
        $user = $request->session()->get('auth_user', []);
        $userId = (int) ($user['id_user'] ?? 0);

        if (preg_match('/cari|dokumen|temukan|judul|keyword|kata kunci/i', $lower)) {
            return $this->searchDocumentsReply($message, $userId);
        }

        if (preg_match('/upload|unggah|cara|submit/i', $lower)) {
            return [
                'text' => 'Cara upload dokumen: buka menu Unggah, isi metadata, jalankan screening, lalu simpan dokumen.',
                'html' => '<p><strong>📤 Cara Upload Dokumen</strong></p><ol><li>Buka menu <strong>Unggah</strong>.</li><li>Isi metadata dokumen.</li><li>Lakukan screening sampai lolos.</li><li>Klik <strong>Unggah Dokumen Sekarang</strong>.</li></ol>',
            ];
        }

        if (preg_match('/rangkum|statistik|rekap|summary/i', $lower)) {
            return $this->summaryReply($userId);
        }

        if (preg_match('/turnitin|plagiasi|similar|similarity/i', $lower)) {
            return [
                'text' => 'Standar Turnitin SIPORA: < 20% aman, 20-30% perlu review, > 30% wajib revisi.',
                'html' => '<p><strong>✅ Standar Turnitin SIPORA</strong></p><p>Berikut penilaian similarity index:</p><ul><li>&lt; 20% → aman</li><li>20-30% → perlu review</li><li>&gt; 30% → wajib revisi</li></ul>',
            ];
        }

        if (preg_match_all('/\b(\d{1,3})\s*%/u', $lower, $percentMatches)) {
            return $this->percentageReply(array_map('intval', $percentMatches[1]));
        }

        if (preg_match('/notifikasi|notif/i', $lower)) {
            $count = $userId
                ? DB::table('notifications')->where('user_id', $userId)->where('is_read', 0)->count()
                : 0;

            return [
                'text' => 'Anda memiliki ' . $count . ' notifikasi belum dibaca.',
                'html' => '<p><strong>🔔 Notifikasi</strong></p><p>Anda memiliki <strong>' . $count . '</strong> notifikasi belum dibaca.</p>',
            ];
        }

        return [
            'text' => 'Saya bisa membantu mencari dokumen, menjelaskan upload, merangkum statistik, dan menjelaskan standar Turnitin.',
            'html' => '<p><strong>👋 Halo! Saya SIPORA AI</strong></p><p>Saya bisa bantu pencarian dokumen, upload, statistik, notifikasi, dan standar Turnitin.</p>',
        ];
    }

    private function searchDocumentsReply(string $message, int $userId): array
    {
        $keyword = trim(preg_replace('/^(cari|dokumen|temukan|judul|keyword|kata kunci)\s*/i', '', $message));
        $query = DB::table('dokumen as d')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->leftJoin('master_tema as t', 'd.id_tema', '=', 't.id_tema')
            ->leftJoin('master_status_dokumen as s', 'd.status_id', '=', 's.status_id')
            ->select('d.judul', 'd.kata_kunci', 'y.tahun', 't.nama_tema', 's.nama_status')
            ->orderByDesc('d.tgl_unggah')
            ->limit(3);

        if ($keyword !== '') {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('d.judul', 'like', '%' . $keyword . '%')
                    ->orWhere('d.kata_kunci', 'like', '%' . $keyword . '%');
            });
        }

        if ($userId > 0) {
            $query->where('d.uploader_id', $userId);
        }

        $documents = $query->get();

        if ($documents->isEmpty()) {
            return [
                'text' => 'Tidak ada dokumen yang cocok.',
                'html' => '<p><strong>📋 Hasil Pencarian Dokumen</strong></p><p>Tidak ada dokumen yang cocok.</p>',
            ];
        }

        $items = $documents->map(function ($doc) {
            return '<li><strong>' . e($doc->judul) . '</strong><br><em>Tema: ' . e($doc->nama_tema ?? '-') . ' · Tahun: ' . e($doc->tahun ?? '-') . '</em><br>Status: ' . e($doc->nama_status ?? '-') . '</li>';
        })->implode('');

        return [
            'text' => 'Saya menemukan ' . $documents->count() . ' dokumen.',
            'html' => '<p><strong>📋 Hasil Pencarian Dokumen</strong></p><ol>' . $items . '</ol>',
        ];
    }

    private function summaryReply(int $userId): array
    {
        $query = DB::table('dokumen');

        if ($userId > 0) {
            $query->where('uploader_id', $userId);
        }

        $total = (clone $query)->count();
        $approved = (clone $query)->where('status_id', 5)->count();
        $pending = (clone $query)->whereIn('status_id', [1, 2])->count();
        $rejected = (clone $query)->where('status_id', 4)->count();
        $avgTurnitin = (int) round((float) ((clone $query)->avg('turnitin') ?? 0));

        return [
            'text' => 'Total ' . $total . ' dokumen, disetujui ' . $approved . ', pending ' . $pending . ', ditolak ' . $rejected . '.',
            'html' => '<p><strong>📊 Statistik Dokumen</strong></p><ul><li>Total dokumen: <strong>' . $total . '</strong></li><li>Disetujui: <strong>' . $approved . '</strong></li><li>Pending: <strong>' . $pending . '</strong></li><li>Ditolak: <strong>' . $rejected . '</strong></li><li>Rata-rata Turnitin: <strong>' . $avgTurnitin . '%</strong></li></ul>',
        ];
    }

    private function percentageReply(array $values): array
    {
        $values = array_values(array_unique($values));
        sort($values);

        $lines = array_map(function (int $value) {
            return '<li>' . $value . '% → ' . $this->percentLabel($value) . '</li>';
        }, $values);

        $summary = implode('', $lines);
        $assessment = count($values) > 1 ? $this->percentLabel(max($values)) : $this->percentLabel($values[0]);
        $text = 'Pada standar Turnitin SIPORA, ' . implode(', ', array_map(fn($v) => $v . '%', $values)) . ' ' . $assessment . '.';

        return [
            'text' => $text,
            'html' => '<p><strong>📌 Penilaian Persentase Turnitin</strong></p><ul>' . $summary . '</ul><p><strong>Catatan:</strong> < 20% → aman<br>20-30% → perlu review<br>&gt; 30% → wajib revisi</p>',
        ];
    }

    private function percentLabel(int $value): string
    {
        if ($value < 20) {
            return 'aman';
        }

        if ($value <= 30) {
            return 'perlu review';
        }

        return 'wajib revisi';
    }
}
