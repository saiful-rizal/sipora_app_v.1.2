<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPORA | Dokumen Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <div class="bg-animation"><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div></div>

  @include('components.navbar')
  @include('components.header_documents')
  @include('components.top_menu')

  <div class="upload-container">
    @if(session('delete_success'))
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><div><strong>Berhasil!</strong> Dokumen berhasil dihapus.</div></div>
    @endif

    @if($errors->has('delete_error'))
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i><div><strong>Error!</strong> {{ $errors->first('delete_error') }}</div></div>
    @endif

    <div class="upload-form-card">
      <div class="upload-form-header">
        <i class="bi bi-folder-fill"></i>
        <h4>Dokumen Saya</h4>
        <div class="ms-auto"><span class="badge bg-primary">{{ count($my_documents) }} Dokumen</span></div>
      </div>

      @if(count($my_documents) === 0)
        <div class="text-center py-5">
          <i class="bi bi-inbox" style="font-size:64px;color:#ccc;"></i>
          <h5 class="mt-3">Belum Ada Dokumen</h5>
          <p class="text-muted">Anda belum mengunggah dokumen apa pun.</p>
          <a href="{{ route('upload.index') }}" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Unggah Dokumen</a>
        </div>
      @else
        <div class="table-responsive mt-3">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Turnitin</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($my_documents as $doc)
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $doc->judul }}</div>
                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($doc->abstrak ?? '-', 80) }}</small>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($doc->tgl_unggah)->format('d M Y H:i') }}</td>
                  <td><span class="badge {{ $doc->status_badge }}">{{ $doc->status_name ?? 'Unknown' }}</span></td>
                  <td>{{ (int)($doc->turnitin ?? 0) }}%</td>
                  <td class="text-end d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-primary" href="{{ $doc->download_url }}" download><i class="bi bi-download"></i></a>
                    <form method="POST" action="{{ route('documents.delete', ['id' => $doc->dokumen_id]) }}" onsubmit="return confirm('Hapus dokumen ini?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
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
