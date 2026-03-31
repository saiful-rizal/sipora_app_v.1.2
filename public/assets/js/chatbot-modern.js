(function () {
  const body = document.getElementById('chatbotBody');
  const form = document.getElementById('chatbotForm');
  const input = document.getElementById('chatInput');
  const resetBtn = document.getElementById('resetChatBtn');
  const endpoint = document.body.dataset.chatbotEndpoint;
  const resetEndpoint = document.body.dataset.chatbotResetEndpoint;
  const engineBadge = document.getElementById('engineBadge');
  const charCounter = document.getElementById('charCounter');
  const csrf = form?.querySelector('input[name="_token"]')?.value || '';

  if (!body || !form || !input) return;

  let typingEl = null;
  let history = [];
  try {
    history = JSON.parse(document.body.dataset.chatHistory || '[]');
    if (!Array.isArray(history)) history = [];
  } catch (e) {
    history = [];
  }

  function nowTime() {
    const d = new Date();
    return `${String(d.getHours()).padStart(2, '0')}.${String(d.getMinutes()).padStart(2, '0')}`;
  }

  function setMode(source) {
    const online = source === 'gpt';
    engineBadge.className = `mode-badge ${online ? 'mode-gpt' : 'mode-local'}`;
    engineBadge.innerHTML = online
      ? '<i class="bi bi-cloud-check"></i> Online GPT'
      : '<i class="bi bi-cpu"></i> Local Mode';
  }

  function scrollBottom() {
    body.scrollTop = body.scrollHeight;
  }

  function addMessage(text, role) {
    const wrap = document.createElement('div');
    wrap.className = `msg ${role}`;

    const textEl = document.createElement('div');
    textEl.textContent = text;
    wrap.appendChild(textEl);

    const timeEl = document.createElement('div');
    timeEl.className = 'msg-time';
    timeEl.textContent = nowTime();
    wrap.appendChild(timeEl);

    body.appendChild(wrap);
    scrollBottom();
    return wrap;
  }

  function addRecommendations(list) {
    if (!Array.isArray(list) || list.length === 0) return;

    const wrap = addMessage('Ini yang bisa kamu baca dulu:', 'bot');
    const recList = document.createElement('div');
    recList.className = 'rec-list';

    list.forEach((item) => {
      const card = document.createElement('div');
      card.className = 'rec-item';

      const title = document.createElement('h6');
      title.textContent = item.judul || 'Tanpa Judul';
      card.appendChild(title);

      const meta = document.createElement('div');
      meta.className = 'rec-meta';
      const jenis = (item.jenis_dokumen || 'dokumen').replaceAll('_', ' ');
      meta.innerHTML = `<span>${jenis}</span><span><i class="bi bi-eye"></i> ${item.view_count || 0}</span><span>${item.nama_jurusan || '-'}</span><span>${item.tahun || '-'}</span>`;
      card.appendChild(meta);
      recList.appendChild(card);
    });

    wrap.appendChild(recList);
    scrollBottom();
  }

  function showTyping() {
    hideTyping();
    typingEl = document.createElement('div');
    typingEl.className = 'msg bot';
    typingEl.innerHTML = '<div class="typing"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>';
    body.appendChild(typingEl);
    scrollBottom();
  }

  function hideTyping() {
    if (typingEl && typingEl.parentNode) typingEl.parentNode.removeChild(typingEl);
    typingEl = null;
  }

  function autoGrow() {
    input.style.height = 'auto';
    input.style.height = `${Math.min(input.scrollHeight, 160)}px`;
    charCounter.textContent = `${input.value.length} karakter`;
  }

  async function sendPrompt(message) {
    addMessage(message, 'user');
    showTyping();

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ message })
      });

      const data = await response.json();
      hideTyping();

      if (!data.success) {
        addMessage(data.message || 'Maaf, ada kendala.', 'bot');
        setMode('local');
        return;
      }

      addMessage(data.reply || 'Siap.', 'bot');
      if (data.mode === 'recommendation') {
        addRecommendations(data.recommendations || []);
      }
      setMode(data.source || 'local');
    } catch (error) {
      hideTyping();
      addMessage('Server belum merespons. Coba lagi sebentar.', 'bot');
      setMode('local');
    }
  }

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const message = input.value.trim();
    if (!message) return;

    input.value = '';
    autoGrow();
    await sendPrompt(message);
  });

  input.addEventListener('input', autoGrow);
  input.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();
      form.requestSubmit();
    }
  });

  document.querySelectorAll('.quick-chip[data-prompt]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const prompt = btn.getAttribute('data-prompt') || '';
      if (!prompt) return;
      await sendPrompt(prompt);
    });
  });

  resetBtn?.addEventListener('click', async () => {
    try {
      await fetch(resetEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({})
      });

      body.innerHTML = '';
      addMessage('Chat direset. Yuk mulai lagi.', 'bot');
      setMode('local');
    } catch (error) {
      addMessage('Gagal reset chat. Coba lagi.', 'bot');
    }
  });

  if (history.length > 0) {
    history.forEach((item) => addMessage(item.content || '', item.role === 'assistant' ? 'bot' : 'user'));
  } else {
    addMessage('Halo. Ceritakan apa yang sedang kamu butuhkan, aku bantu pelan-pelan ya.', 'bot');
  }

  autoGrow();
})();
