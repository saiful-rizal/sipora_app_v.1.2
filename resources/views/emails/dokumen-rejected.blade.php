<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0">
<div style="max-width:600px;margin:40px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">
    <div style="background:#dc2626;padding:32px;text-align:center">
        <h1 style="color:#fff;margin:0;font-size:24px">SIPORA</h1>
        <p style="color:#fecaca;margin:4px 0 0">Sistem Informasi Politeknik Negeri Jember</p>
    </div>
    <div style="padding:32px">
        <p style="font-size:16px;color:#374151">Yth. <strong>{{ $namaPengirim }}</strong>,</p>
        <p style="color:#374151;line-height:1.6">
            Mohon maaf, dokumen Anda <strong style="color:#dc2626">tidak dapat disetujui</strong> oleh tim admin SIPORA.
        </p>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:16px;margin:16px 0">
            <p style="margin:0;color:#991b1b;font-size:14px">📄 <strong>Judul Dokumen:</strong></p>
            <p style="margin:4px 0 0;color:#7f1d1d;font-size:15px;font-weight:bold">{{ $judulDokumen }}</p>
        </div>
        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:16px;margin:16px 0">
            <p style="margin:0;color:#92400e;font-size:14px">💬 <strong>Alasan Penolakan:</strong></p>
            <p style="margin:8px 0 0;color:#78350f;font-size:14px;line-height:1.6">{{ $alasanReject }}</p>
        </div>
        @if($filePath)
        <p style="color:#374151;font-size:14px;line-height:1.6">
            File dokumen Anda dilampirkan dalam email ini. Silakan perbaiki dan unggah kembali melalui SIPORA.
        </p>
        @endif
        <p style="color:#6b7280;font-size:14px;line-height:1.6">
            Jika ada pertanyaan, silakan hubungi admin SIPORA.
        </p>
    </div>
    <div style="background:#f9fafb;padding:16px;text-align:center;border-top:1px solid #e5e7eb">
        <p style="color:#9ca3af;font-size:12px;margin:0">Email ini dikirim otomatis oleh sistem SIPORA. Mohon tidak membalas email ini.</p>
    </div>
</div>
</body>
</html>
