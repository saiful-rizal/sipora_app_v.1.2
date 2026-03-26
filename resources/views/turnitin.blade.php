<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPORA | Laporan Turnitin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <div class="bg-animation"><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div></div>
  @include('components.navbar')
  @include('components.header_turnitin')
  @include('components.top_menu')

  <div class="upload-container">
    <div class="filter-bar-clean">
      <div class="filter-title"><i class="bi bi-funnel"></i> Filter Skor</div>
      <div class="filter-options-clean">
        <a href="{{ route('documents.turnitin', ['score' => 'all']) }}" class="filter-option-clean {{ $score_filter === 'all' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('documents.turnitin', ['score' => 'none']) }}" class="filter-option-clean {{ $score_filter === 'none' ? 'active' : '' }}">Tanpa Skor</a>
        <a href="{{ route('documents.turnitin', ['score' => 'low']) }}" class="filter-option-clean {{ $score_filter === 'low' ? 'active' : '' }}">0-20%</a>
        <a href="{{ route('documents.turnitin', ['score' => 'medium']) }}" class="filter-option-clean {{ $score_filter === 'medium' ? 'active' : '' }}">21-40%</a>
        <a href="{{ route('documents.turnitin', ['score' => 'high']) }}" class="filter-option-clean {{ $score_filter === 'high' ? 'active' : '' }}">>40%</a>
      </div>
      <div class="filter-actions-clean">
        <a class="btn-export-clean" href="{{ route('documents.turnitin.export', ['score' => $score_filter]) }}"><i class="bi bi-download"></i> Export Excel</a>
      </div>
    </div>

    <div class="table-clean-container">
      <div class="table-header-clean">
        <h5><i class="bi bi-shield-check"></i> Daftar Turnitin</h5>
        <span class="result-count-clean">{{ count($documents) }} dokumen</span>
      </div>
      @if(count($documents) === 0)
        <div class="empty-state-clean"><i class="bi bi-inbox"></i><h6>Tidak ada data</h6><p>Belum ada dokumen sesuai filter.</p></div>
      @else
        <div class="table-responsive">
          <table class="table-clean">
            <thead><tr><th>Dokumen</th><th>Tema</th><th>Tahun</th><th>Skor</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($documents as $doc)
              @php
                $score = (int)($doc->turnitin ?? 0);
                $scoreClass = $score <= 20 ? 'bg-success' : ($score <= 40 ? 'bg-warning text-dark' : 'bg-danger');
              @endphp
              <tr>
                <td><strong>{{ $doc->judul }}</strong><small class="d-block text-muted">{{ $doc->uploader_name ?? '-' }}</small></td>
                <td>{{ $doc->nama_tema ?? '-' }}</td>
                <td>{{ $doc->year_id ?? '-' }}</td>
                <td><span class="badge {{ $scoreClass }}">{{ $score }}%</span></td>
                <td><span class="badge badge-info">{{ $doc->status_name ?? 'Unknown' }}</span></td>
                <td class="d-flex gap-2"><a class="btn btn-sm btn-outline-primary" href="{{ route('documents.detail', ['id' => $doc->dokumen_id]) }}"><i class="bi bi-eye"></i></a><a class="btn btn-sm btn-outline-success" href="{{ route('documents.download', ['id' => $doc->dokumen_id]) }}"><i class="bi bi-download"></i></a></td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  @include('components.footer_upload')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
