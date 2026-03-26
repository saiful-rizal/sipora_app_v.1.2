(function () {
  const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

  async function openDocumentModal(documentId) {
    const detailBody = document.getElementById('detailBody');
    const detailTitle = document.getElementById('detailTitle');
    const detailEndpoint = document.body.dataset.searchDetailEndpoint || '';

    detailModal.show();
    detailBody.innerHTML = 'Memuat...';

    try {
      const response = await fetch(`${detailEndpoint}?id=${documentId}`);
      const data = await response.json();
      if (!data.success) {
        detailBody.innerHTML = `<div class="alert alert-danger">${data.message || 'Gagal memuat detail'}</div>`;
        return;
      }

      const doc = data.document;
      detailTitle.textContent = doc.judul;

      const formatFileSize = (bytes) => {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
      };

      detailBody.innerHTML = `
        <div class="document-detail-info">
          <div class="detail-section">
            <h6 class="detail-label">Judul Dokumen</h6>
            <p class="detail-value">${doc.judul || '-'}</p>
          </div>
          <div class="detail-section">
            <h6 class="detail-label">Abstrak</h6>
            <p class="detail-value" style="line-height: 1.6;">${doc.abstrak || '-'}</p>
          </div>
          <div class="detail-row">
            <div class="detail-section flex-1">
              <h6 class="detail-label">Jurusan</h6>
              <p class="detail-value">${doc.nama_jurusan || '-'}</p>
            </div>
            <div class="detail-section flex-1">
              <h6 class="detail-label">Program Studi</h6>
              <p class="detail-value">${doc.nama_prodi || '-'}</p>
            </div>
          </div>
          <div class="detail-row">
            <div class="detail-section flex-1">
              <h6 class="detail-label">Tema</h6>
              <p class="detail-value">${doc.nama_tema || '-'}</p>
            </div>
            <div class="detail-section flex-1">
              <h6 class="detail-label">Tahun</h6>
              <p class="detail-value">${doc.tahun || '-'}</p>
            </div>
          </div>
          <div class="detail-section">
            <h6 class="detail-label">Penulis</h6>
            <p class="detail-value"><i class="bi bi-person-circle"></i> ${doc.uploader_name || '-'}</p>
          </div>
          <div class="detail-section">
            <h6 class="detail-label">Ukuran File</h6>
            <p class="detail-value"><i class="bi bi-file"></i> ${formatFileSize(doc.file_size || 0)}</p>
          </div>
        </div>
        <div class="modal-actions">
          <a class="btn btn-primary btn-sm" href="${doc.download_url}" download><i class="bi bi-download"></i> Unduh Dokumen</a>
        </div>
      `;
    } catch (e) {
      detailBody.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> Terjadi kesalahan saat memuat detail.</div>';
    }
  }

  function setSearchViewMode(mode) {
    const documentGrid = document.getElementById('documentGrid');
    const buttons = document.querySelectorAll('.view-btn');

    if (documentGrid) {
      documentGrid.classList.remove('list-view', 'grid-view');
      documentGrid.classList.add(mode === 'list' ? 'list-view' : 'grid-view');
    }

    buttons.forEach(btn => {
      btn.classList.toggle('active', btn.dataset.view === mode);
    });

    localStorage.setItem('searchViewMode', mode);
  }

  // Initialize view mode from localStorage
  window.addEventListener('DOMContentLoaded', function () {
    const savedMode = localStorage.getItem('searchViewMode') || 'grid';
    setSearchViewMode(savedMode);
  });

  window.openDocumentModal = openDocumentModal;
  window.setSearchViewMode = setSearchViewMode;
})();
