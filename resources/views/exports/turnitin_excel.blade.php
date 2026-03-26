<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link href="{{ asset('assets/css/turnitin-excel.css') }}" rel="stylesheet">
  <!-- INLINE CSS MOVED TO public/assets/css/turnitin-excel.css
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  -->
</head>
<body>
  <h3>LAPORAN TURNITIN</h3>
  <p>Filter: {{ $score_filter }}</p>
  <p>Tanggal Export: {{ now()->format('d M Y H:i:s') }}</p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Judul Dokumen</th>
        <th>Tema</th>
        <th>Tahun</th>
        <th>Pengunggah</th>
        <th>Divisi</th>
        <th>Skor Turnitin</th>
        <th>Status</th>
        <th>Tanggal Unggah</th>
      </tr>
    </thead>
    <tbody>
      @forelse($documents as $index => $doc)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $doc->judul ?? '-' }}</td>
          <td>{{ $doc->nama_tema ?? '-' }}</td>
          <td>{{ $doc->year_id ?? '-' }}</td>
          <td>{{ $doc->uploader_name ?? '-' }}</td>
          <td>{{ $doc->nama_divisi ?? '-' }}</td>
          <td>{{ (int)($doc->turnitin ?? 0) }}%</td>
          <td>{{ $doc->status_name ?? '-' }}</td>
          <td>{{ $doc->tgl_unggah ? \Carbon\Carbon::parse($doc->tgl_unggah)->format('d M Y H:i:s') : '-' }}</td>
        </tr>
      @empty
        <tr><td colspan="9" style="text-align:center;">Tidak ada dokumen.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
