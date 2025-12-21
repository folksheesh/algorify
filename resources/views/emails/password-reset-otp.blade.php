<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 12px;
            padding: 25px 40px;
            border-radius: 12px;
            display: inline-block;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
            font-size: 14px;
        }
        .timer {
            color: #dc3545;
            font-weight: 600;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎓 Algorify</div>
            <h1>Reset Password</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Halo {{ $name }},</p>
            
            <p class="message">
                Kami menerima permintaan untuk mereset password akun Algorify Anda.<br>
                Gunakan kode OTP berikut untuk melanjutkan:
            </p>
            
            <div class="otp-box">{{ $otp }}</div>
            
            <div class="warning">
                ⏱️ Kode ini akan <span class="timer">kadaluarsa dalam 15 menit</span>.<br>
                Jangan bagikan kode ini kepada siapapun.
            </div>
            
            <p class="message">
                Jika Anda tidak meminta reset password, abaikan email ini dan jangan bagikan kode di atas kepada siapapun.
            </p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Algorify. Semua hak dilindungi.<br>
            Email ini dikirim secara otomatis, mohon tidak membalas.
        </div>
    </div>
</body>
</html>
