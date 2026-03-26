<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPORA | Riwayat Upload</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <div class="bg-animation"><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div></div>

  @include('components.navbar')
  @include('components.header_riwayat')
  @include('components.top_menu')

  <div class="upload-container">
    <div class="filter-bar-clean">
      <div class="filter-title"><i class="bi bi-clock-history"></i> Filter Riwayat</div>
      <div class="filter-options-clean">
        <a href="{{ route('documents.history', ['date' => 'all']) }}" class="filter-option-clean {{ $date_filter === 'all' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('documents.history', ['date' => 'today']) }}" class="filter-option-clean {{ $date_filter === 'today' ? 'active' : '' }}">Hari Ini</a>
        <a href="{{ route('documents.history', ['date' => 'week']) }}" class="filter-option-clean {{ $date_filter === 'week' ? 'active' : '' }}">7 Hari Terakhir</a>
        <a href="{{ route('documents.history', ['date' => 'month']) }}" class="filter-option-clean {{ $date_filter === 'month' ? 'active' : '' }}">30 Hari Terakhir</a>
      </div>
      <div class="filter-actions-clean">
        <a class="btn-export-clean" href="{{ route('documents.history.export', ['date' => $date_filter]) }}"><i class="bi bi-download"></i> Export CSV</a>
      </div>
    </div>

    <div class="table-clean-container">
      <div class="table-header-clean">
        <h5><i class="bi bi-clock-history"></i> Riwayat Upload</h5>
        <span class="result-count-clean">{{ count($history) }} aktivitas</span>
      </div>

      @if(count($history) === 0)
        <div class="empty-state-clean">
          <i class="bi bi-clock-history"></i>
          <h6>Tidak Ada Riwayat</h6>
          <p>Belum ada aktivitas upload yang sesuai dengan filter.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table-clean">
            <thead>
              <tr>
                <th>Waktu</th>
                <th>Dokumen</th>
                <th>Status</th>
                <th>Turnitin</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($history as $item)
                <tr>
                  <td>
                    <div class="time-info-clean">
                      <div>{{ \Carbon\Carbon::parse($item->tgl_unggah)->format('d M Y') }}</div>
                      <small>{{ \Carbon\Carbon::parse($item->tgl_unggah)->format('H:i:s') }}</small>
                    </div>
                  </td>
                  <td>
                    <div class="doc-info-clean">
                      <strong>{{ $item->judul }}</strong>
                      <small class="d-block text-muted">{{ $item->nama_tema ?? '-' }}</small>
                    </div>
                  </td>
                  <td><span class="badge {{ $item->status_badge }}">{{ $item->status_name ?? 'Unknown' }}</span></td>
                  <td>{{ (int)($item->turnitin ?? 0) }}%</td>
                  <td><a href="{{ $item->download_url }}" class="btn btn-sm btn-outline-primary" download><i class="bi bi-download"></i></a></td>
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
