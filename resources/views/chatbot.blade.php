<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | Teman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/chatbot-modern.css') }}" rel="stylesheet">
</head>
<body
    data-chatbot-endpoint="{{ route('chatbot.recommend') }}"
    data-chatbot-reset-endpoint="{{ route('chatbot.reset') }}"
    data-chat-history='@json($chat_history ?? [])'
>
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    @include('components.navbar')

    <div class="chat-modern-page">
        <aside class="quick-panel">
            <h5><i class="bi bi-lightbulb"></i> Mulai Cepat</h5>
            <button type="button" class="quick-chip" data-prompt="Bantu aku mulai skripsi">Bantu aku mulai skripsi</button>
            <button type="button" class="quick-chip" data-prompt="Lagi stuck, bantu susun langkah">Lagi stuck, bantu susun langkah</button>
            <button type="button" class="quick-chip" data-prompt="Rekomendasi buku ML pemula">Rekomendasi buku ML pemula</button>
            <button type="button" class="quick-chip" data-prompt="Ide topik tugas akhir">Ide topik tugas akhir</button>
        </aside>

        <section class="chat-modern-shell">
            <div class="chat-modern-header">
                <div class="title-wrap">
                    <span class="icon-wrap"><i class="bi bi-stars"></i></span>
                    <div>
                        <h4>Sipora AI</h4>
                        <p>Ngobrol natural, bisa lokal maupun online GPT</p>
                    </div>
                </div>
                <div class="header-actions">
                    <span id="engineBadge" class="mode-badge mode-local"><i class="bi bi-cpu"></i> Local Mode</span>
                    <button id="resetChatBtn" type="button" class="btn-reset">Reset</button>
                </div>
            </div>

            <div class="chat-modern-body" id="chatbotBody"></div>

            <form class="chat-modern-form" id="chatbotForm">
                @csrf
                <div class="input-wrap">
                    <textarea id="chatInput" rows="1" placeholder="Ketik pesan... (Enter untuk kirim, Shift+Enter untuk baris baru)" autocomplete="off" required></textarea>
                    <small class="tip">Tip: bilang "rekomendasi" kalau mau daftar buku/dokumen</small>
                </div>
                <button class="btn-send" type="submit" aria-label="Kirim pesan"><i class="bi bi-send"></i></button>
            </form>
            <div class="char-counter" id="charCounter">0 karakter</div>
        </div>
    </div>

    <script src="{{ asset('assets/js/chatbot-modern.js') }}" defer></script>
</body>
</html>
