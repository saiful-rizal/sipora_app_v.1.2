const dashboardConfig = document.body?.dataset || {};
const dashboardDetailUrl = dashboardConfig.detailUrl || '/dashboard/detail';
const dashboardShareBase = dashboardConfig.shareBase || '/dashboard';

let currentDocumentId = null;
let currentDocumentData = null;

const emptyText = '-';

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function normalizeText(value, fallback = emptyText) {
    if (value === null || value === undefined || value === '') return fallback;
    return String(value);
}

function formatFileSize(bytes) {
    const fileBytes = Number(bytes || 0);
    if (!fileBytes) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(fileBytes) / Math.log(k));
    return `${parseFloat((fileBytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
}

function formatDate(dateString) {
    if (!dateString) return emptyText;

    const date = new Date(dateString);
    if (Number.isNaN(date.getTime())) return emptyText;

    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getDocumentFromCard(documentId) {
    const card = document.querySelector(`[data-id="${documentId}"]`);
    if (!card) return null;

    const fileUrl = card.dataset.fileUrl || '';
    const fullTitle = card.dataset.fullTitle || card.dataset.title || 'Pratinjau Dokumen';
    const fullDescription = card.dataset.fullDescription || card.dataset.description || '';

    return {
        dokumen_id: documentId,
        judul: fullTitle,
        abstrak: fullDescription,
        download_url: fileUrl,
        file_type: card.dataset.fileType || '',
        file_name: card.dataset.fileName || (fileUrl ? fileUrl.split('/').pop() : ''),
        file_size: parseInt(card.dataset.fileSize || '0', 10),
        uploader_name: card.dataset.uploaderName || '',
        uploader_email: card.dataset.uploaderEmail || '',
        nama_jurusan: card.dataset.namaJurusan || '',
        nama_prodi: card.dataset.namaProdi || '',
        nama_tema: card.dataset.namaTema || '',
        tahun: card.dataset.tahun || '',
        status_id: card.dataset.statusId || '',
        status_name: card.dataset.statusName || '',
        status_badge: card.dataset.statusBadge || 'badge-secondary',
        turnitin: card.dataset.turnitin || '',
        tgl_unggah: card.dataset.tglUnggah || '',
        updated_at: card.dataset.updatedAt || ''
    };
}

function loadPreviewFromUrl(fileUrl, fileType) {
    const container = document.getElementById('documentViewerContainer');
    if (!container) return;

    const normalizedType = (fileType || '').toLowerCase();

    if (normalizedType === 'pdf' && fileUrl) {
        container.innerHTML = `<iframe src="${escapeHtml(fileUrl)}" id="documentViewer"></iframe>`;
        return;
    }

    container.innerHTML = `
        <div class="preview-placeholder">
            <i class="bi bi-file-earmark-arrow-down"></i>
            <h4>Pratinjau Tidak Tersedia</h4>
            <p>Jenis file ini tidak dapat ditampilkan. Silakan unduh file untuk melihatnya.</p>
            <a href="${escapeHtml(fileUrl)}" download class="btn-action btn-primary">
                <i class="bi bi-download"></i> Unduh File
            </a>
        </div>
    `;
}

function loadPreview(doc) {
    if (!doc) return;
    loadPreviewFromUrl(doc.download_url || '', doc.file_type || '');
}

function buildKeywordsHtml(doc) {
    const keywordsList = Array.isArray(doc?.keywords) ? doc.keywords : [];
    if (keywordsList.length === 0) {
        return '<p class="text-muted">Tidak ada kata kunci</p>';
    }

    return keywordsList
        .map((keyword) => `<span class="keyword-badge">${escapeHtml(keyword)}</span>`)
        .join('');
}

function displayDocumentInfo(doc) {
    const infoContent = document.getElementById('documentInfoContent');
    const modalTitle = document.getElementById('modalTitle');

    if (!infoContent || !modalTitle || !doc) return;

    const safeTitle = normalizeText(doc.judul, 'Detail Dokumen');
    const safeStatusName = normalizeText(doc.status_name, 'Unknown');
    const safeStatusBadge = normalizeText(doc.status_badge, 'badge-secondary');
    const safeAbstrak = normalizeText(doc.abstrak, 'Tidak ada abstrak');

    modalTitle.textContent = safeTitle;

    const shortDescription = safeAbstrak.length > 200
        ? `${safeAbstrak.substring(0, 200)}...`
        : safeAbstrak;

    const keywordsHtml = buildKeywordsHtml(doc);

    infoContent.innerHTML = `
        <div class="document-summary-card">
            <div class="document-summary-header">
                <div class="document-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="document-title-container">
                    <h3 class="document-title">${escapeHtml(safeTitle)}</h3>
                    <div class="document-meta-info">
                        <div class="document-meta-item">
                            <i class="bi bi-person"></i>
                            <span>${escapeHtml(normalizeText(doc.uploader_name))}</span>
                        </div>
                        <div class="document-meta-item">
                            <i class="bi bi-calendar3"></i>
                            <span>${escapeHtml(formatDate(doc.tgl_unggah))}</span>
                        </div>
                        <div class="document-meta-item">
                            <i class="bi bi-folder"></i>
                            <span>${escapeHtml(normalizeText(doc.nama_jurusan))}</span>
                        </div>
                    </div>
                    <div class="document-badges">
                        <span class="document-badge ${escapeHtml(safeStatusBadge)}">${escapeHtml(safeStatusName)}</span>
                        ${doc.turnitin ? `<span class="document-badge badge-info">Turnitin: ${escapeHtml(doc.turnitin)}%</span>` : ''}
                        <span class="document-badge badge-secondary">${escapeHtml((doc.file_type || '-').toUpperCase())}</span>
                    </div>
                </div>
            </div>
            <div class="document-description" id="documentDescription">${escapeHtml(shortDescription)}</div>
            ${safeAbstrak.length > 200 ? '<div class="document-description-toggle" onclick="toggleDescription()">Baca selengkapnya</div>' : ''}
        </div>

        <div class="detail-container">
            <div class="detail-section">
                <h6 class="detail-section-title"><i class="bi bi-file-earmark-text"></i> Informasi Dokumen</h6>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">ID Dokumen</span>
                        <span class="detail-value">#${escapeHtml(normalizeText(doc.dokumen_id, '0'))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="detail-value badge ${escapeHtml(safeStatusBadge)}">${escapeHtml(safeStatusName)}</span>
                    </div>
                    ${doc.turnitin ? `
                    <div class="detail-item">
                        <span class="detail-label">Turnitin</span>
                        <span class="detail-value">${escapeHtml(doc.turnitin)}%</span>
                    </div>` : ''}
                    ${doc.turnitin_file ? `
                    <div class="detail-item">
                        <span class="detail-label">File Turnitin</span>
                        <span class="detail-value">${escapeHtml(doc.turnitin_file)}</span>
                    </div>` : ''}
                    <div class="detail-item">
                        <span class="detail-label">Nama File</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.file_name))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tipe File</span>
                        <span class="detail-value">${escapeHtml((doc.file_type || '-').toUpperCase())}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ukuran File</span>
                        <span class="detail-value">${escapeHtml(formatFileSize(doc.file_size))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ID Divisi</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.id_divisi))}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h6 class="detail-section-title"><i class="bi bi-person"></i> Informasi Pengunggah</h6>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Nama</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.uploader_name))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.uploader_email))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Unggah</span>
                        <span class="detail-value">${escapeHtml(formatDate(doc.tgl_unggah))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Terakhir Diperbarui</span>
                        <span class="detail-value">${escapeHtml(formatDate(doc.updated_at))}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h6 class="detail-section-title"><i class="bi bi-card-text"></i> Abstrak</h6>
                <p class="mb-0">${escapeHtml(safeAbstrak)}</p>
            </div>

            <div class="detail-section">
                <h6 class="detail-section-title"><i class="bi bi-book"></i> Informasi Akademik</h6>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Jurusan</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.nama_jurusan))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Program Studi</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.nama_prodi))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tema</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.nama_tema))}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tahun</span>
                        <span class="detail-value">${escapeHtml(normalizeText(doc.tahun))}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h6 class="detail-section-title"><i class="bi bi-tags"></i> Kata Kunci</h6>
                <div class="keyword-list">${keywordsHtml}</div>
            </div>
        </div>
    `;
}

function switchTab(tabName) {
    const tab = document.querySelector(`[data-tab="${tabName}"]`);
    if (!tab) return;

    document.querySelectorAll('.document-detail-tab').forEach((item) => item.classList.remove('active'));
    document.querySelectorAll('.document-detail-content').forEach((item) => item.classList.remove('active'));

    tab.classList.add('active');

    const tabPanel = document.getElementById(`${tabName}-tab`);
    if (tabPanel) tabPanel.classList.add('active');

    if (tabName === 'preview' && currentDocumentData) {
        loadPreview(currentDocumentData);
    }
}

function showDocumentPreview(documentId, fileUrl, fileType) {
    const modal = document.getElementById('documentModal');
    const modalTitle = document.getElementById('modalTitle');
    if (!modal || !modalTitle) return;

    currentDocumentId = documentId;
    currentDocumentData = getDocumentFromCard(documentId) || {
        dokumen_id: documentId,
        judul: 'Pratinjau Dokumen',
        abstrak: '',
        download_url: fileUrl || '',
        file_type: fileType || ''
    };

    if (!currentDocumentData.download_url && fileUrl) {
        currentDocumentData.download_url = fileUrl;
    }
    if (!currentDocumentData.file_type && fileType) {
        currentDocumentData.file_type = fileType;
    }

    modalTitle.textContent = currentDocumentData.judul || 'Pratinjau Dokumen';
    modal.style.display = 'block';

    switchTab('preview');
    loadPreviewFromUrl(currentDocumentData.download_url, currentDocumentData.file_type);

    try {
        displayDocumentInfo(currentDocumentData);
    } catch (error) {
        console.error('displayDocumentInfo error:', error);
    }
}

function showDocumentDetail(documentId) {
    const modal = document.getElementById('documentModal');
    const modalTitle = document.getElementById('modalTitle');
    if (!modal || !modalTitle) return;

    currentDocumentId = documentId;
    currentDocumentData = getDocumentFromCard(documentId) || {
        dokumen_id: documentId,
        judul: 'Detail Dokumen',
        abstrak: ''
    };

    modalTitle.textContent = currentDocumentData.judul || 'Detail Dokumen';
    modal.style.display = 'block';
    switchTab('info');
    displayDocumentInfo(currentDocumentData);

    fetch(`${dashboardDetailUrl}?id=${documentId}`)
        .then((response) => response.json())
        .then((data) => {
            if (data && data.success && data.document) {
                currentDocumentData = data.document;
                displayDocumentInfo(currentDocumentData);
            }
        })
        .catch((error) => {
            console.error('Error fetching full details:', error);
        });
}

function closeDocumentModal() {
    const modal = document.getElementById('documentModal');
    if (modal) {
        modal.style.display = 'none';
    }
    currentDocumentId = null;
    currentDocumentData = null;
}

function toggleDescription() {
    const description = document.getElementById('documentDescription');
    const toggle = document.querySelector('.document-description-toggle');

    if (!description || !toggle || !currentDocumentData || !currentDocumentData.abstrak) return;

    if (description.classList.contains('expanded')) {
        description.classList.remove('expanded');
        description.textContent = `${currentDocumentData.abstrak.substring(0, 200)}...`;
        toggle.textContent = 'Baca selengkapnya';
        return;
    }

    description.classList.add('expanded');
    description.textContent = currentDocumentData.abstrak;
    toggle.textContent = 'Tampilkan lebih sedikit';
}

function setViewMode(mode) {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const documentGrid = document.getElementById('documentGrid');

    if (!gridViewBtn || !listViewBtn || !documentGrid) return;

    if (mode === 'grid') {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        documentGrid.classList.remove('document-list');
        return;
    }

    gridViewBtn.classList.remove('active');
    listViewBtn.classList.add('active');
    documentGrid.classList.add('document-list');
}

function shareDocument() {
    if (!currentDocumentId || !currentDocumentData) return;

    const url = `${dashboardShareBase}?share_id=${currentDocumentId}`;

    if (navigator.share) {
        navigator.share({
            title: currentDocumentData.judul || 'Dokumen',
            text: 'Lihat dokumen ini di SIPORA',
            url
        });
        return;
    }

    navigator.clipboard.writeText(url).then(() => {
        alert('Link dokumen telah disalin ke clipboard!');
    });
}

window.showDocumentPreview = showDocumentPreview;
window.showDocumentDetail = showDocumentDetail;
window.closeDocumentModal = closeDocumentModal;
window.setViewMode = setViewMode;
window.toggleDescription = toggleDescription;
window.shareDocument = shareDocument;

window.onclick = function (event) {
    const modal = document.getElementById('documentModal');
    if (event.target === modal) {
        closeDocumentModal();
    }
};

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeDocumentModal();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.document-detail-tab').forEach((tab) => {
        tab.addEventListener('click', function () {
            switchTab(this.getAttribute('data-tab'));
        });
    });
});
