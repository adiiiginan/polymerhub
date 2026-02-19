<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Kata Sandi - Jaya Niaga Semesta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #4f46e5;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .header-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .content {
            padding: 25px;
            color: #333333;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }

        .footer {
            background-color: #f4f6f8;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #888888;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('backend/assets/media/logos/logo.png') }}" alt="Logo Perusahaan">
            <p class="header-title">Reset Kata Sandi</p>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda di platform Jaya
                Niaga Semesta.</p>

            <div class="warning">
                <strong>Peringatan:</strong> Tautan reset ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak meminta
                reset kata sandi, abaikan email ini.
            </div>

            <p>Klik tombol di bawah ini untuk mereset kata sandi Anda:</p>
            <a href="{{ $resetUrl }}" class="btn">Reset Kata Sandi</a>

            <p>Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:</p>
            <p
                style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace;">
                {{ $resetUrl }}
            </p>

            <p>Terima kasih,<br>
                Tim Jaya Niaga Semesta</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Jaya Niaga Semesta. Semua Hak Dilindungi.
        </div>
    </div>
</body>

</html>
