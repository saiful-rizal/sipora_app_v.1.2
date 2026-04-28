<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi SIPORA</title>
</head>
<body style="margin:0;padding:0;background:#f0f3fb;font-family:Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f0f3fb;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 8px 28px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:28px 32px;background:linear-gradient(130deg,#0b1b4d 0%,#1a56d6 70%,#2979ff 100%);color:#ffffff;">
                            <h1 style="margin:0;font-size:24px;line-height:1.3;">Reset Kata Sandi SIPORA</h1>
                            <p style="margin:10px 0 0;font-size:14px;line-height:1.6;color:rgba(255,255,255,0.86);">
                                Permintaan reset kata sandi untuk akun Anda telah kami terima.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;">Halo {{ $namaPengguna }},</p>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;">
                                Klik tombol di bawah ini untuk membuat kata sandi baru akun SIPORA Anda.
                            </p>
                            <p style="margin:0 0 24px;font-size:15px;line-height:1.7;">
                                Link ini hanya berlaku selama <strong>{{ $expiryMinutes }} menit</strong> atau 1 jam sejak email ini dikirim.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 24px;">
                                <tr>
                                    <td align="center" style="border-radius:10px;background:#1a56d6;">
                                        <a href="{{ $resetLink }}" style="display:inline-block;padding:14px 24px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">
                                            Reset Kata Sandi
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#475569;">
                                Link reset kata sandi Anda:
                            </p>
                            <p style="margin:0 0 16px;font-size:13px;line-height:1.7;word-break:break-all;">
                                <a href="{{ $resetLink }}" style="color:#1a56d6;">{{ $resetLink }}</a>
                            </p>

                            <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#475569;">
                                Jika tombol tidak bisa diklik, salin dan buka tautan di atas pada browser:
                            </p>
                            <p style="margin:0 0 24px;font-size:13px;line-height:1.7;color:#64748b;">
                                Link tetap berlaku selama {{ $expiryMinutes }} menit.
                            </p>

                            <div style="padding:16px 18px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                                <p style="margin:0;font-size:13px;line-height:1.7;color:#1e3a8a;">
                                    Jika Anda tidak meminta reset kata sandi, abaikan email ini. Kata sandi Anda tidak akan berubah.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
