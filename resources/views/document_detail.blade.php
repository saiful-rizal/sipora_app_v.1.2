<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPORA | Detail Dokumen</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <div class="bg-animation"><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div></div>
  @include('components.navbar')
  @include('components.header_dashboard')

  <div class="container py-4" style="max-width:1000px;">
    <div class="upload-form-card p-4">
      <h2 class="mb-2">{{ $document->judul ?? 'Tanpa Judul' }}</h2>
      <div class="d-flex flex-wrap gap-2 mb-3">
        <span class="badge {{ $status_badge }}">{{ $document->status_name ?? 'Unknown' }}</span>
        <span class="badge bg-secondary">{{ $document->nama_jurusan ?? '-' }}</span>
        <span class="badge bg-secondary">{{ $document->nama_prodi ?? '-' }}</span>
        <span class="badge bg-secondary">{{ $document->nama_tema ?? '-' }}</span>
        <span class="badge bg-info text-dark">Turnitin: {{ (int)($document->turnitin ?? 0) }}%</span>
      </div>

      <p class="text-muted mb-3"><i class="bi bi-person-circle"></i> {{ $document->uploader_name ?? '-' }} • <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($document->tgl_unggah)->format('d M Y H:i') }}</p>

      <h5>Abstrak</h5>
      <p>{{ $document->abstrak ?? 'Tidak ada abstrak.' }}</p>

      <div class="d-flex gap-2 mt-4">
        <a class="btn btn-primary" href="{{ route('documents.download', ['id' => $document->dokumen_id]) }}"><i class="bi bi-download"></i> Unduh Dokumen</a>
        <a class="btn btn-outline-secondary" href="{{ route('browser.index') }}"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>

      @if(!empty($download_url) && strtolower(pathinfo((string)($document->file_path ?? ''), PATHINFO_EXTENSION)) === 'pdf')
        <div class="mt-4">
          <h5>Pratinjau PDF</h5>
          <iframe src="{{ $download_url }}" style="width:100%;height:70vh;border:1px solid #ddd;border-radius:8px;"></iframe>
        </div>
      @endif
    </div>
  </div>

  @include('components.footer_upload')
</body>
</html>
