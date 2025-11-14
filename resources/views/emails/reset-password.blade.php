<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
        }

        .content {
            margin-bottom: 30px;
        }

        .content p {
            margin: 15px 0;
        }

        .reset-button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }

        .reset-button:hover {
            background-color: #2563eb;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }

        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p style="margin: 10px 0 0 0; color: #6b7280;">TK Teratai Kota Cirebon</p>
        </div>

        <div class="content">
            <p>Halo {{ $userName }},</p>

            <p>Kami menerima permintaan untuk reset password akun Anda. Jika Anda tidak melakukan permintaan ini,
                silakan abaikan email ini.</p>

            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-button">Reset Password Sekarang</a>
            </p>

            <p>Atau copy link berikut ke browser Anda:</p>
            <p style="word-break: break-all; background-color: #f3f4f6; padding: 10px; border-radius: 4px;">
                {{ $resetUrl }}
            </p>

            <div class="warning">
                <strong>⚠️ Catatan Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Link reset password akan hangus dalam 60 menit</li>
                    <li>Gunakan link ini hanya untuk reset password Anda</li>
                    <li>Jangan bagikan link ini kepada orang lain</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>© 2025 TK Teratai Kota Cirebon. Semua hak dilindungi.</p>
            <p>Email ini dikirim karena adanya permintaan reset password. Jika Anda tidak memintanya, abaikan email ini.
            </p>
        </div>
    </div>
</body>

</html>
