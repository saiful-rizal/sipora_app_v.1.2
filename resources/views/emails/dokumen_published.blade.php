<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:0; }
        .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .header  { background:#0a1628; padding:28px 32px; text-align:center; }
        .header h1 { color:#d4af55; margin:0; font-size:20px; letter-spacing:1px; }
        .header p  { color:#aac0e0; margin:6px 0 0; font-size:12px; }
        .body    { padding:28px 32px; color:#333; line-height:1.7; }
        .nomor-box { background:#fdf8ee; border:1px solid #c9a84c; border-radius:6px; padding:14px 18px; margin:18px 0; }
        .nomor-box .label { font-size:11px; color:#888; text-transform:uppercase; letter-spacing:1px; }
        .nomor-box .value { font-size:18px; font-weight:bold; color:#0a1628; letter-spacing:1px; margin-top:4px; }
        .doc-title { background:#f0f4ff; border-left:4px solid #0a1628; padding:12px 16px; margin:16px 0; border-radius:0 4px 4px 0; font-style:italic; color:#1a1a1a; }
        .footer  { background:#f9f9f9; border-top:1px solid #eee; padding:16px 32px; text-align:center; font-size:11px; color:#aaa; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>🎉 Dokumen Dipublikasi</h1>
        <p>Repositori Institusi — Universitas Negeri Surabaya</p>
    </div>
    <div class="body">
        <p>Yth. <strong>{{ $namaPengirim }}</strong>,</p>
        <p>
            Kami dengan bangga memberitahukan bahwa dokumen/karya ilmiah Anda telah
            <strong>resmi dipublikasi</strong> dalam Repositori Institusi Universitas Negeri Surabaya.
        </p>

        <div class="nomor-box">
            <div class="label">Nomor Surat Publikasi</div>
            <div class="value">{{ $nomorSurat }}</div>
        </div>

        <p>Judul Dokumen:</p>
        <div class="doc-title">{{ $judulDokumen }}</div>

        <p>
            Surat keterangan publikasi resmi dapat diperoleh dari admin
            repositori atau dengan menghubungi unit layanan kami.
        </p>

        <p>Terima kasih atas kontribusi Anda dalam pengembangan ilmu pengetahuan.</p>

        <p>Salam hormat,<br>
        <strong>Unit Repositori Karya Ilmiah</strong><br>
        Universitas Negeri Surabaya</p>
    </div>
    <div class="footer">
        Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.
    </div>
</div>
</body>
</html>
