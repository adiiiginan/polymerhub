<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account Under Review</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f6f7;
            font-family: Arial, sans-serif;
        }

        .email-container {
            max-width: 520px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .header {
            text-align: center;
            background: #4f46e5;
            padding: 25px 20px;
            color: #ffffff;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .content {
            padding: 25px 28px;
            color: #333;
            line-height: 1.6;
            font-size: 15px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            margin: 18px 0;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            background: #f5f6f7;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="email-container">

        <div class="header">
            <img src="https://example.com/logo.png" alt="Company Logo">
            <h2>Your Account Is Under Review</h2>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $nama }}</strong>,</p>

            <p>Thank you for registering on our platform.
                Your account is currently <strong>under review</strong> by our verification team.</p>

            <div class="info-box">
                <p><strong>Name:</strong> {{ $nama }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Company:</strong> {{ $perusahaan }}</p>
            </div>

            <p>We will notify you via email once your account has been approved and is ready to use.</p>

            <a href="{{ url('/') }}" class="btn">Visit Website</a>
        </div>

        <div class="footer">
            © {{ date('Y') }} Jaya Niaga Semesta. All rights reserved.
        </div>

    </div>
</body>

</html>
