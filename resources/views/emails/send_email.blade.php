<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracer Study Account Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
        }
        .email-card {
            background-color: #13161d;
            color: #ffffff;
            overflow: hidden;
        }
        .header {
            padding: 20px;
            display: flex;
            align-items: center;
            background-color: #13161d;
            border-bottom: 1px solid #2a2f3a;
        }
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #ffffff;
        }
        .content {
            padding: 30px 20px 30px 40px; /* Increased left padding */
            background-color: #13161d;
            position: relative;
        }
        /* Blue vertical line using border-left instead of absolute positioning */
        .content-wrapper {
            border-left: 4px solid #3476ff;
            padding-left: 20px;
        }
        h2 {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        p {
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .account-info {
            background-color: #1c202b;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-label {
            font-size: 14px;
            color: #8e9cb2;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #ffffff;
            margin-bottom: 15px;
        }
        .cta-button {
            display: inline-block;
            background-color: #3476ff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #8e9cb2;
            background-color: #13161d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-card">
            <div class="header">
                <!-- Using text instead of image for logo -->
                <div class="logo-text">TRACER STUDY</div>
                <img src="{{asset('assets/images/logo/logoStis.png')}}" alt="Tracer Study Logo" class="logo">
            </div>
            <div class="content">
                <div class="content-wrapper">
                    @if(isset($data['body']) && $data['body'])
                        <!-- Use template body if available -->
                        {!! $data['body'] !!}

                        
                    @else
                        <!-- Fallback to old format -->
                        <h2>Halo {{ $data['nama'] }}!</h2>

                        <p>Berikut akun tracer study kamu:</p>

                        <div class="account-info">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ $data['email'] }}</div>

                            <div class="info-label">Password:</div>
                            <div class="info-value">{{$data['password']}}</div>
                        </div>

                        <p>Yuk, langsung isi surveimu! Terima kasih </p>

                        <a href="{{ $data['link'] }}" class="cta-button">Login Sekarang</a>
                    @endif
                </div>
            </div>

            <div class="footer">
                © 2025 Tracer Study. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>