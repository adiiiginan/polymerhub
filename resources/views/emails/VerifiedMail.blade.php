<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Akun Anda Telah Diverifikasi</title>
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
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
        }

        .footer {
            background-color: #f4f6f8;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('backend/assets/media/logos/logo.png') }}" alt="Logo Perusahaan">
            <p class="header-title">Akun Anda Telah Aktif</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $nama }}</strong>,</p>
            <p>Selamat! Akun Anda telah <strong>berhasil diverifikasi</strong> oleh tim admin kami.</p>

            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #333;">Detail Akun Anda:</h3>
                <p style="margin: 5px 0;"><strong>Nama:</strong> {{ $nama }}</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> {{ $email }}</p>
                <p style="margin: 5px 0;"><strong>Perusahaan:</strong> {{ $perusahaan }}</p>
            </div>

            <p>Sekarang Anda sudah bisa masuk dan mulai menggunakan layanan kami.</p>
            <a href="{{ url('/jns.co.id') }}" class="btn">Masuk ke Akun</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Jaya Niaga Semesta. Semua Hak Dilindungi.
        </div>
    </div>
</body>

</html>
