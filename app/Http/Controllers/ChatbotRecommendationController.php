<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Throwable;

class ChatbotRecommendationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return redirect()->route('login')->with('login_error', 'Silakan login terlebih dahulu.');
        }

        return view('chatbot', [
            'chat_history' => $this->getHistory($request, (int) $user['id_user']),
        ]);
    }

    public function recommend(Request $request): JsonResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $query = trim((string) $request->input('message', ''));
        if ($query === '') {
            return response()->json([
                'success' => true,
                'reply' => 'Halo. Kita bisa ngobrol apa saja dulu. Kalau kamu butuh rekomendasi buku, tinggal bilang ya.',
                'recommendations' => [],
                'source' => 'local',
                'mode' => 'conversation',
            ]);
        }

        $wantsRecommendations = $this->wantsRecommendations($query);
        $catalog = $wantsRecommendations ? $this->findCandidateDocuments($query) : collect();
        $history = $this->getHistory($request, (int) $user['id_user']);

        $gptResult = $this->chatWithGpt($query, $catalog, $history, $wantsRecommendations);
        if ($gptResult !== null) {
            if (($gptResult['source'] ?? 'gpt') === 'api_error') {
                $localReply = $this->buildLocalReply($query, $history, $wantsRecommendations);
                $localRecommendations = $wantsRecommendations
                    ? ($catalog->isEmpty() ? $this->fallbackRecommendations() : $catalog->take(5)->values())
                    : collect();

                $this->appendToHistory($request, (int) $user['id_user'], 'user', $query);
                $this->appendToHistory($request, (int) $user['id_user'], 'assistant', $localReply);

                return response()->json([
                    'success' => true,
                    'reply' => $localReply,
                    'recommendations' => $localRecommendations,
                    'source' => 'local',
                    'mode' => $wantsRecommendations ? 'recommendation' : 'conversation',
                ]);
            }

            $this->appendToHistory($request, (int) $user['id_user'], 'user', $query);
            $this->appendToHistory($request, (int) $user['id_user'], 'assistant', $gptResult['reply']);

            return response()->json([
                'success' => true,
                'reply' => $gptResult['reply'],
                'recommendations' => $gptResult['recommendations'],
                'source' => $gptResult['source'] ?? 'gpt',
                'mode' => $wantsRecommendations ? 'recommendation' : 'conversation',
            ]);
        }

        if ($wantsRecommendations) {
            $recommendations = $catalog->take(5)->values();
            if ($recommendations->isEmpty()) {
                $recommendations = $this->fallbackRecommendations();
            }

            $fallbackReply = 'Siap. Ini rekomendasi awal berdasarkan data yang tersedia. Kalau mau, kamu bisa kasih topik lebih spesifik.';
            $this->appendToHistory($request, (int) $user['id_user'], 'user', $query);
            $this->appendToHistory($request, (int) $user['id_user'], 'assistant', $fallbackReply);

            return response()->json([
                'success' => true,
                'reply' => $fallbackReply,
                'recommendations' => $recommendations,
                'source' => 'local',
                'mode' => 'recommendation',
            ]);
        }

        $offlineReply = 'Bisa. Kita ngobrol dulu saja. Kalau nanti kamu butuh rekomendasi buku, bilang topiknya ya.';
        $this->appendToHistory($request, (int) $user['id_user'], 'user', $query);
        $this->appendToHistory($request, (int) $user['id_user'], 'assistant', $offlineReply);

        return response()->json([
            'success' => true,
            'reply' => $offlineReply,
            'recommendations' => [],
            'source' => 'local',
            'mode' => 'conversation',
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $user = $this->sessionUser($request);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->session()->forget($this->historyKey((int) $user['id_user']));

        return response()->json([
            'success' => true,
            'message' => 'Riwayat chat berhasil direset.',
        ]);
    }

    private function findCandidateDocuments(string $query)
    {
        $keywords = $this->extractKeywords($query);

        $baseQuery = DB::table('dokumen as d')
            ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->where('d.status_id', 5)
            ->select([
                'd.dokumen_id',
                'd.judul',
                'd.kata_kunci',
                'd.jenis_dokumen',
                'd.view_count',
                'j.nama_jurusan',
                'y.tahun',
                'd.tgl_unggah',
            ]);

        $matched = collect();
        if (!empty($keywords)) {
            $matched = (clone $baseQuery)
                ->where(function ($where) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $where->orWhere('d.judul', 'like', '%' . $keyword . '%')
                            ->orWhere('d.abstrak', 'like', '%' . $keyword . '%')
                            ->orWhere('d.kata_kunci', 'like', '%' . $keyword . '%');
                    }
                })
                ->orderByDesc('d.view_count')
                ->orderByDesc('d.tgl_unggah')
                ->limit(12)
                ->get();
        }

        if ($matched->count() < 12) {
            $excludeIds = $matched->pluck('dokumen_id')->all();
            $popular = (clone $baseQuery)
                ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                    $query->whereNotIn('d.dokumen_id', $excludeIds);
                })
                ->orderByDesc('d.view_count')
                ->orderByDesc('d.tgl_unggah')
                ->limit(12 - $matched->count())
                ->get();

            $matched = $matched->concat($popular);
        }

        return $matched->values();
    }

    private function chatWithGpt(string $query, $catalog, array $history, bool $wantsRecommendations): ?array
    {
        $apiKey = (string) config('services.openai.api_key', '');
        $baseUrl = rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/');
        $model = (string) config('services.openai.model', 'gpt-4o-mini');

        if ($apiKey === '') {
            return [
                'reply' => 'OPENAI_API_KEY belum diisi. Isi dulu di file .env agar saya bisa ngobrol seperti GPT.',
                'recommendations' => collect(),
                    'source' => 'api_error',
            ];
        }

        $catalogText = $catalog->map(function ($doc) {
            $jenis = str_replace('_', ' ', (string) ($doc->jenis_dokumen ?? 'dokumen'));
            $keywords = (string) ($doc->kata_kunci ?? '');
            $jurusan = (string) ($doc->nama_jurusan ?? '-');
            $tahun = (string) ($doc->tahun ?? '-');
            $views = (int) ($doc->view_count ?? 0);

            return sprintf(
                '- id:%d | judul:%s | jenis:%s | jurusan:%s | tahun:%s | views:%d | kata_kunci:%s',
                (int) $doc->dokumen_id,
                (string) $doc->judul,
                $jenis,
                $jurusan,
                $tahun,
                $views,
                $keywords
            );
        })->implode("\n");

        if ($catalogText === '') {
            $catalogText = '- tidak ada katalog yang dipakai pada pesan ini';
        }

        $mode = $wantsRecommendations ? 'rekomendasi' : 'percakapan';
        $systemPrompt = 'Kamu asisten chatbot kampus yang berbicara hangat dan natural seperti manusia. Gunakan bahasa Indonesia santai-sopan, relevan dengan konteks chat sebelumnya, dan tidak kaku. Prinsip jawaban: (1) pahami maksud pengguna dulu, (2) jawab ringkas tapi bernyawa, (3) sesekali beri pertanyaan lanjutan yang membantu dialog, (4) hindari template robotik seperti "berikut rekomendasi" jika tidak diminta. Jika pengguna curhat/galau, validasi perasaan secara empatik tanpa menghakimi. Jika pengguna minta rekomendasi buku, baru gunakan katalog. Wajib output JSON valid tanpa markdown: {"reply":"...","selected_ids":[1,2,3]}.';

        $historyMessages = collect($history)
            ->filter(fn ($item) => isset($item['role'], $item['content']))
            ->take(-12)
            ->map(function ($item) {
                return [
                    'role' => $item['role'] === 'assistant' ? 'assistant' : 'user',
                    'content' => (string) $item['content'],
                ];
            })
            ->values()
            ->all();

        $userPrompt = "Mode saat ini: {$mode}\n\nKatalog dokumen:\n{$catalogText}\n\nPesan terbaru pengguna: {$query}\n\nAturan output: jika mode percakapan, selected_ids harus kosong dan fokus ke dialog natural. Jika mode rekomendasi, pilih maksimal 5 id dokumen paling relevan dan jelaskan singkat kenapa cocok.";

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $historyMessages,
            [['role' => 'user', 'content' => $userPrompt]]
        );

        try {
            $response = Http::timeout(30)
                ->withToken($apiKey)
                ->post($baseUrl . '/chat/completions', [
                    'model' => $model,
                    'temperature' => 0.7,
                    'top_p' => 0.9,
                    'presence_penalty' => 0.3,
                    'frequency_penalty' => 0.2,
                    'messages' => $messages,
                ]);

            if (!$response->successful()) {
                $errorCode = (string) data_get($response->json(), 'error.code', '');
                $errorType = (string) data_get($response->json(), 'error.type', '');
                $errorMessage = (string) data_get($response->json(), 'error.message', '');
                $status = $response->status();

                $reply = match (true) {
                    $status === 401 => 'Koneksi ke OpenAI gagal karena API key tidak valid. Cek OPENAI_API_KEY di file .env.',
                    $status === 429 && ($errorCode === 'insufficient_quota' || str_contains(strtolower($errorType), 'insufficient_quota'))
                        => 'Koneksi GPT aktif, tapi kuota OpenAI kamu habis (insufficient_quota). Silakan isi saldo/upgrade billing dulu.',
                    $status === 429 => 'Permintaan ke OpenAI sedang terlalu banyak (rate limit). Coba beberapa saat lagi.',
                    default => 'OpenAI belum bisa merespons saat ini. Coba lagi sebentar.',
                };

                if ($reply === 'OpenAI belum bisa merespons saat ini. Coba lagi sebentar.' && $errorMessage !== '') {
                    $reply .= ' Detail: ' . $errorMessage;
                }

                return [
                    'reply' => $reply,
                    'recommendations' => collect(),
                    'source' => 'api_error',
                ];
            }

            $content = (string) data_get($response->json(), 'choices.0.message.content', '');
            if ($content === '') {
                return null;
            }

            $parsed = $this->decodeJsonFromModel($content);
            if (!is_array($parsed)) {
                // Jika model tidak mengikuti format JSON, tetap gunakan teks mentah agar percakapan tidak terjebak fallback statis.
                return [
                    'reply' => trim($content),
                    'recommendations' => collect(),
                    'source' => 'gpt',
                ];
            }

            $ids = collect((array) ($parsed['selected_ids'] ?? []))
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->unique()
                ->values();

            $recommendations = collect();
            if ($wantsRecommendations) {
                $recommendations = $catalog
                    ->whereIn('dokumen_id', $ids)
                    ->take(5)
                    ->values();

                if ($recommendations->isEmpty()) {
                    $recommendations = $catalog->take(5)->values();
                }
            }

            $reply = trim((string) ($parsed['reply'] ?? 'Ini rekomendasi bacaan yang paling relevan buat kamu.'));
            if ($reply === '') {
                $reply = 'Ini rekomendasi bacaan yang paling relevan buat kamu.';
            }

            return [
                'reply' => $reply,
                'recommendations' => $recommendations,
                'source' => 'gpt',
            ];
        } catch (Throwable $e) {
            return [
                'reply' => 'Koneksi ke layanan GPT sedang bermasalah. Detail: ' . $e->getMessage(),
                'recommendations' => collect(),
                'source' => 'api_error',
            ];
        }
    }

    private function buildLocalReply(string $query, array $history, bool $wantsRecommendations): string
    {
        $text = strtolower(trim($query));
        $seed = abs(crc32($text . '|' . count($history)));

        $pick = static function (array $choices) use ($seed): string {
            if (empty($choices)) {
                return '';
            }

            return $choices[$seed % count($choices)];
        };

        if ($wantsRecommendations) {
            return $pick([
                'Siap, aku bantu carikan bacaan yang relevan. Biar lebih pas, kamu lagi fokus ke topik apa dan untuk level apa (laporan magang, tugas akhir, skripsi, atau tesis)?',
                'Oke, kita cari referensi yang paling nyambung. Kamu pengen yang lebih dasar dulu atau yang langsung teknis untuk tugasmu?',
                'Bisa banget. Sebutkan topik inti dan level dokumenmu, nanti aku pilihkan bacaan yang paling cocok.',
            ]);
        }

        if (preg_match('/\b(hai|halo|hello|pagi|siang|sore|malam)\b/i', $text) === 1) {
            return $pick([
                'Hai juga. Senang ngobrol sama kamu. Lagi pengen bahas apa hari ini?',
                'Halo. Aku di sini buat nemenin ngobrol. Mau cerita soal kuliah, tugas, atau hal lain?',
                'Hai. Asik bisa ngobrol bareng. Ada yang lagi kepikiran sekarang?',
            ]);
        }

        if (preg_match('/\b(terima kasih|makasih|thanks)\b/i', $text) === 1) {
            return $pick([
                'Sama-sama. Kalau mau lanjut cerita atau butuh referensi, tinggal bilang ya.',
                'Siap, senang bisa bantu. Kalau ada yang mau dibahas lagi, aku standby.',
                'Dengan senang hati. Kita bisa lanjut kapan pun kamu mau.',
            ]);
        }

        if (preg_match('/\b(sedang apa|lagi apa|gimana kabar|apa kabar)\b/i', $text) === 1) {
            return $pick([
                'Lagi siap nemenin kamu ngobrol. Kamu sendiri lagi ngerjain apa sekarang?',
                'Aku lagi fokus nemenin kamu di sini. Hari ini kamu lagi berkutat sama apa?',
                'Lagi siap bantu kamu kapan aja. Mau ngobrol santai atau bahas tugas?',
            ]);
        }

        if (preg_match('/\b(capek|lelah|bingung|stres|pusing)\b/i', $text) === 1) {
            return $pick([
                'Wajar kok kalau lagi capek. Coba tarik napas sebentar, lalu kita pecah masalahmu jadi langkah kecil. Bagian mana yang paling bikin berat sekarang?',
                'Aku paham rasanya waktu beban lagi numpuk. Kalau kamu mau, kita urutkan satu per satu biar lebih ringan.',
                'Itu normal, kamu nggak sendirian. Mulai dari satu hal dulu: bagian mana yang paling urgent buat diberesin?',
            ]);
        }

        $lastAssistant = collect($history)
            ->reverse()
            ->first(fn ($item) => ($item['role'] ?? '') === 'assistant');

        if ($lastAssistant && isset($lastAssistant['content'])) {
            return $pick([
                'Aku nangkep maksudmu. Mau kita dalami dari sisi konsep dulu atau langsung ke contoh praktis biar cepat kepakai?',
                'Oke, konteksnya sudah kebayang. Kamu maunya pembahasan ringkas atau detail langkah demi langkah?',
                'Sip, kita bisa lanjut dari sini. Kamu lebih nyaman pakai contoh nyata atau poin-poin inti dulu?',
            ]);
        }

        return $pick([
            'Menarik. Ceritain sedikit konteksmu, nanti aku bantu jawab dengan lebih tepat.',
            'Boleh, kita bahas. Kasih aku sedikit latar belakang biar jawabannya lebih pas.',
            'Oke, aku siap bantu. Kamu ingin arah jawabannya lebih praktis atau lebih konseptual?',
        ]);
    }

    private function historyKey(int $userId): string
    {
        return 'chatbot_history_' . $userId;
    }

    private function getHistory(Request $request, int $userId): array
    {
        $history = $request->session()->get($this->historyKey($userId), []);
        if (!is_array($history)) {
            return [];
        }

        return array_values(array_filter($history, function ($item) {
            return is_array($item)
                && isset($item['role'], $item['content'])
                && in_array($item['role'], ['user', 'assistant'], true)
                && is_string($item['content'])
                && trim($item['content']) !== '';
        }));
    }

    private function appendToHistory(Request $request, int $userId, string $role, string $content): void
    {
        $history = $this->getHistory($request, $userId);
        $history[] = [
            'role' => $role,
            'content' => trim($content),
        ];

        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }

        $request->session()->put($this->historyKey($userId), $history);
    }

    private function decodeJsonFromModel(string $raw): ?array
    {
        $trimmed = trim($raw);

        if (str_starts_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```[a-zA-Z0-9]*\s*/', '', $trimmed) ?? $trimmed;
            $trimmed = preg_replace('/\s*```$/', '', $trimmed) ?? $trimmed;
        }

        $decoded = json_decode($trimmed, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $trimmed, $matches) === 1) {
            $decoded = json_decode($matches[0], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    private function wantsRecommendations(string $query): bool
    {
        $text = strtolower($query);

        $keywords = [
            'rekomendasi',
            'sarankan',
            'saran buku',
            'referensi',
            'bacaan',
            'buku',
            'dokumen terkait',
            'topik untuk dibaca',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function fallbackRecommendations()
    {
        return DB::table('dokumen as d')
            ->leftJoin('master_jurusan as j', 'd.id_jurusan', '=', 'j.id_jurusan')
            ->leftJoin('master_tahun as y', 'd.year_id', '=', 'y.year_id')
            ->where('d.status_id', 5)
            ->select([
                'd.dokumen_id',
                'd.judul',
                'd.kata_kunci',
                'd.jenis_dokumen',
                'd.view_count',
                'j.nama_jurusan',
                'y.tahun',
            ])
            ->orderByDesc('d.view_count')
            ->orderByDesc('d.tgl_unggah')
            ->limit(5)
            ->get();
    }

    private function extractKeywords(string $query): array
    {
        $normalized = strtolower(preg_replace('/[^a-z0-9\s]/i', ' ', $query) ?? '');
        $parts = array_values(array_filter(array_map('trim', explode(' ', $normalized))));

        $stopWords = [
            'dan', 'atau', 'yang', 'untuk', 'dengan', 'tentang', 'saya', 'ingin', 'cari', 'carikan',
            'buku', 'dokumen', 'the', 'of', 'to', 'in', 'on', 'for', 'is', 'are', 'a', 'an', 'rekomendasi',
        ];

        $filtered = array_values(array_filter($parts, static function (string $word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords, true);
        }));

        return array_slice(array_unique($filtered), 0, 8);
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
