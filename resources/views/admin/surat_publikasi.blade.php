<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Surat Publikasi — {{ $dokumen->nomor_surat }}</title>

<style>
@page {
    size: A4;
    margin: 30mm 40mm 30mm 40mm; /* atas kanan bawah kiri = 3,4,3,4 cm */
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 9.5pt;
    color: #111;
    background: #fff;
}

.page {
    width: 100%;
}

/* ===== BORDER GANDA BIRU ===== */
.border-outer {
    padding: 3px;
}
.border-inner {
    padding: 10px 14px 12px;
}

/* ===== KOP ===== */
.kop-table {
    width: 100%;
    border-collapse: collapse;
}
.kop-table td {
    vertical-align: middle;
    padding: 2px 4px;
}
.kop-logo-cell {
    width: 64px;
    text-align: center;
}
.kop-logo-cell img {
    width: 130px;
    height: 130px;
    display: block;
    margin: 0 auto;
}
.logo-placeholder {
    width: 56px;
    height: 56px;
    border: 2px solid #1a4580;
    border-radius: 50%;
    margin: 0 auto;
    text-align: center;
    font-size: 6.5pt;
    font-weight: bold;
    color: #1a4580;
    line-height: 1.3;
    padding-top: 14px;
}
.kop-center-cell {
    text-align: center;
    padding: 0 8px;
}
.kop-instansi {
    font-size: 8pt;
    color: #111;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.kop-kampus {
    font-size: 14pt;
    font-weight: bold;
    color: #1a4580;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 1px 0;
}
.kop-alamat {
    font-size: 7.5pt;
    color: #333;
    line-height: 1.55;
    margin-top: 2px;
}

/* Garis pemisah kop */
.kop-line-thick { border-top: 2.5px solid #1a4580; margin: 6px 0 0; }
.kop-line-thin  { border-top: 1px   solid #4a7cc7; margin: 2px 0 0; }

/* ===== JUDUL ===== */
.judul-wrap { text-align: center; margin: 8px 0 7px; }
.judul-label {
    font-size: 7pt;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #666;
}
.judul-surat {
    font-size: 13pt;
    font-weight: bold;
    color: #1a4580;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: underline;
    margin-top: 2px;
}
.judul-sub {
    font-size: 8pt;
    color: #4a7cc7;
    font-weight: bold;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-top: 1px;
}

/* ===== META NOMOR ===== */
.meta-box {
    background: #eef3fc;
    border: 1px solid #4a7cc7;
    padding: 6px 12px;
    margin-bottom: 9px;
}
.meta-table { width: 100%; font-size: 9pt; border-collapse: collapse; }
.meta-table td { padding: 1.5px 3px; vertical-align: top; }
.meta-label { color: #333; font-weight: bold; width: 100px; }
.meta-sep   { width: 12px; color: #333; }
.meta-value { color: #111; font-weight: bold; }
.meta-nomor { font-size: 10pt; letter-spacing: 1px; color: #1a4580; }

/* ===== PEMBUKA ===== */
.pembuka {
    font-size: 9.5pt;
    line-height: 1.75;
    text-align: justify;
    margin-bottom: 8px;
}

/* ===== TABEL IDENTITAS ===== */
.dokumen-header {
    background: #1a4580;
    color: #fff;
    font-weight: bold;
    font-size: 9pt;
    padding: 5px 10px;
    letter-spacing: 0.5px;
}
.dokumen-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9pt;
    border: 1px solid #4a7cc7;
    border-top: none;
}
.dokumen-table tr { border-bottom: 1px solid #c8d8f5; }
.dokumen-table tr:last-child { border-bottom: none; }
.dok-label {
    background: #f0f5ff;
    color: #333;
    font-weight: bold;
    padding: 4px 8px;
    width: 135px;
    vertical-align: top;
    border-right: 1px solid #c8d8f5;
}
.dok-sep   { padding: 4px 4px; color: #333; vertical-align: top; width: 10px; }
.dok-value { padding: 4px 8px; color: #111; vertical-align: top; }
.dok-judul { font-style: italic; font-weight: bold; }
.badge-green  { color: #155724; font-weight: bold; }
.badge-yellow { color: #856404; font-weight: bold; }
.badge-red    { color: #721c24; font-weight: bold; }

/* ===== PENUTUP ===== */
.penutup {
    font-size: 9.5pt;
    line-height: 1.75;
    text-align: justify;
    margin-top: 8px;
}

/* ===== TTD ===== */
.ttd-wrap { margin-top: 200px; width: 100%; display: table; }
.ttd-left  { display: table-cell; width: 50%; vertical-align: top; }
.ttd-right { display: table-cell; width: 50%; vertical-align: top; text-align: center; }
.ttd-kota    { font-size: 9.5pt; margin-bottom: 3px; }
.ttd-jabatan {
    font-size: 9pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #1a4580;
    margin-bottom: 52px;
}
.ttd-nama { font-size: 10pt; font-weight: bold; color: #1a4580; border-top: 1.5px solid #1a4580; padding-top: 3px; }
.ttd-nip  { font-size: 8pt; color: #555; margin-top: 2px; }

/* ===== FOOTER ===== */
.surat-footer {
    border-top: 1px solid #4a7cc7;
    margin-top: 30px;
    padding-top: 5px;
    width: 100%;
    display: table;
    font-size: 7.5pt;
    color: #666;
}
.footer-left   { display: table-cell; text-align: left;  width: 40%; }
.footer-center { display: table-cell; text-align: center; width: 30%; font-style: italic; }
.footer-right  { display: table-cell; text-align: right; width: 30%; }
</style>
</head>

<body>
<div class="page">
<div class="border-outer">
<div class="border-inner">

    {{-- ===== KOP POLIJE ===== --}}
    <table class="kop-table">
        <tr>
            {{-- LOGO KIRI: Polije --}}
            <td class="kop-logo-cell">
                @php $logoPolije = public_path('images/logo_polije.jpeg'); @endphp
                @if(file_exists($logoPolije))
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($logoPolije)) }}" alt="Logo Polije">
                @else
                    <div class="logo-placeholder">LOGO<br>POLIJE</div>
                @endif
            </td>

            {{-- TEKS TENGAH --}}
            <td class="kop-center-cell">
                <div class="kop-instansi">Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi</div>
                <div class="kop-kampus">Politeknik Negeri Jember</div>
                <div class="kop-alamat">
                    Jalan Mastrip Kotak Pos 164 Jember 68101<br>
                    Telp. (0331) 333532-34; Fax. (0331) 333531<br>
                    Email: politeknik@polije.ac.id &nbsp;&bull;&nbsp; Laman: www.polije.ac.id
                </div>
            </td>

            {{-- LOGO KANAN: UPA Perpustakaan --}}
            <td class="kop-logo-cell">
                @php $logoPerp = public_path('images/perpustakaan.jpeg'); @endphp
                @if(file_exists($logoPerp))
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($logoPerp)) }}" alt="Logo Perpustakaan">
                @else
                    <div class="logo-placeholder">UPA<br>PERPUST.</div>
                @endif
            </td>
        </tr>
    </table>
    <div class="kop-line-thick"></div>
    <div class="kop-line-thin"></div>

    {{-- ===== JUDUL ===== --}}
    <div class="judul-wrap">
        <div class="judul-label">Dokumen Resmi Repositori Institusi</div>
        <div class="judul-surat">Surat Keterangan Publikasi</div>
        <div class="judul-sub">Karya Ilmiah Mahasiswa</div>
    </div>

    {{-- ===== META NOMOR ===== --}}
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nomor</td>
                <td class="meta-sep">:</td>
                <td class="meta-value meta-nomor">{{ $dokumen->nomor_surat }}</td>
                <td style="width:16px"></td>
                <td class="meta-label">Tanggal Terbit</td>
                <td class="meta-sep">:</td>
                <td class="meta-value">{{ \Carbon\Carbon::parse($dokumen->published_at)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Sifat</td>
                <td class="meta-sep">:</td>
                <td class="meta-value">Resmi / Terbuka</td>
                <td></td>
                <td class="meta-label">Perihal</td>
                <td class="meta-sep">:</td>
                <td class="meta-value">Publikasi Karya Ilmiah</td>
            </tr>
        </table>
    </div>

    {{-- ===== PEMBUKA ===== --}}
    <p class="pembuka">
        Yang bertanda tangan di bawah ini, Kepala Unit Pelaksana Akademik (UPA) Perpustakaan
        <strong>Politeknik Negeri Jember</strong>, dengan ini menerangkan bahwa
        dokumen/karya ilmiah yang tercantum di bawah ini telah melalui proses seleksi,
        verifikasi, dan pemeriksaan originalitas oleh tim reviewer, serta dinyatakan
        <strong>layak untuk dipublikasikan</strong> secara resmi dalam Repositori Institusi
        Politeknik Negeri Jember.
    </p>

    {{-- ===== IDENTITAS ===== --}}
    <div class="dokumen-header"> Identitas Karya Ilmiah</div>
    <table class="dokumen-table">
        <tr>
            <td class="dok-label">Judul</td>
            <td class="dok-sep">:</td>
            <td class="dok-value dok-judul">{{ $dokumen->judul }}</td>
        </tr>
        <tr>
            <td class="dok-label">Penulis / Uploader</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->uploader->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td class="dok-label">Jurusan</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->jurusan->nama_jurusan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="dok-label">Program Studi</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->prodi->nama_prodi ?? '-' }}</td>
        </tr>
        @if($dokumen->divisi)
        <tr>
            <td class="dok-label">Divisi</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->divisi->nama_divisi }}</td>
        </tr>
        @endif
        <tr>
            <td class="dok-label">Tema</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->tema->nama_tema ?? '-' }}</td>
        </tr>
        <tr>
            <td class="dok-label">Tahun</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->year->tahun ?? $dokumen->year->nama_tahun ?? $dokumen->year->year ?? '-' }}</td>
        </tr>
        <tr>
            <td class="dok-label">Tanggal Unggah</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->tgl_unggah ? \Carbon\Carbon::parse($dokumen->tgl_unggah)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        @if($dokumen->turnitin)
        <tr>
            <td class="dok-label">Similaritas (Turnitin)</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">
                <span class="{{ $dokumen->turnitin <= 20 ? 'badge-green' : ($dokumen->turnitin <= 40 ? 'badge-yellow' : 'badge-red') }}">
                    {{ $dokumen->turnitin }}% &mdash;
                    @if($dokumen->turnitin <= 20) Originalitas Tinggi
                    @elseif($dokumen->turnitin <= 40) Similaritas Sedang
                    @else Perlu Perhatian @endif
                </span>
            </td>
        </tr>
        @endif
        @if($dokumen->kata_kunci)
        <tr>
            <td class="dok-label">Kata Kunci</td>
            <td class="dok-sep">:</td>
            <td class="dok-value">{{ $dokumen->kata_kunci }}</td>
        </tr>
        @endif
        <tr>
            <td class="dok-label">Status Publikasi</td>
            <td class="dok-sep">:</td>
            <td class="dok-value badge-green"> Dipublikasi Resmi</td>
        </tr>
    </table>

    {{-- ===== PENUTUP ===== --}}
    <p class="penutup">
        Surat keterangan ini diterbitkan untuk dapat dipergunakan sebagaimana mestinya,
        sebagai bukti bahwa karya ilmiah tersebut telah terdaftar dan dipublikasikan secara
        resmi dalam Repositori Institusi Politeknik Negeri Jember.
    </p>


    {{-- ===== TTD ===== --}}
    <div class="ttd-wrap">
        <div class="ttd-left"></div>
        <div class="ttd-right">
            <div class="ttd-kota">Jember, {{ \Carbon\Carbon::parse($dokumen->published_at)->translatedFormat('d F Y') }}</div>
            <div class="ttd-jabatan">Kepala UPA Perpustakaan</div>
            <br> <br> <br> <br>
            <div class="ttd-nama">________________________________</div>
            <div class="ttd-nip">NIP. ________________________________</div>
        </div>
    </div>
<br> <br> <br> <br><br><br> <br> <br> <br>
    {{-- ===== FOOTER ===== --}}
    <div class="surat-footer">
        <div class="footer-left">ID: DOC-{{ str_pad($dokumen->dokumen_id, 6, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp; {{ $dokumen->nomor_surat }}</div>
        <div class="footer-center">Diterbitkan oleh Sistem Repositori Polije</div>
        <div class="footer-right">Dicetak: {{ now()->translatedFormat('d F Y') }}</div>
    </div>

</div>
</div>
</div>
</body>
</html>
