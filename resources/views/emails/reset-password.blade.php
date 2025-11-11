<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SIAS</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-height: 60px;
        }
        .button {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://example.com/img/logo-soeradji.png" alt="Logo RSUP Soeradji" class="logo">
        <h1>SISTEM INPUT ARSIP SOERADJI</h1>
    </div>

    <h2>Reset Password SIAS</h2>
    <p>Halo {{ $userName }},</p>
    <p>Kami menerima permintaan reset password untuk akun Anda.</p>
    
    <div style="text-align: center;">
        <a href="{{ $resetLink }}" class="button">Reset Password</a>
    </div>
    
    <p>Link akan kadaluarsa dalam 1 jam. Jika Anda tidak meminta reset password, abaikan email ini.</p>
    
    <div class="footer">
        <p>SIAS - RSUP Soeradji Tirtonogoro</p>
        <p>Jika tombol tidak berfungsi, salin dan tempel link berikut di browser Anda:</p>
        <p>{{ $resetLink }}</p>
    </div>
</body>
</html>