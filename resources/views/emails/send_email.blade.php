<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracer Study Account Information</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 20px 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            width: 100%;
            box-sizing: border-box;
        }
        .email-card {
            background-color: #13161d;
            color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            padding: 25px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #13161d 0%, #1a1f2e 100%);
            border-bottom: 2px solid #3476ff;
        }
        .logo-text {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .logo {
            height: 40px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
        .content {
            padding: 40px 30px;
            background-color: #13161d;
            position: relative;
        }
        .content-wrapper {
            border-left: 4px solid #3476ff;
            padding-left: 25px;
            position: relative;
        }
        .content-wrapper::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(180deg, #3476ff 0%, #667eea 100%);
            border-radius: 4px;
        }
        h2 {
            font-size: 26px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 20px;
            color: #ffffff;
            line-height: 1.3;
        }
        p {
            line-height: 1.6;
            margin-bottom: 20px;
            color: #e1e5e9;
            font-size: 16px;
        }
        .account-info {
            background: linear-gradient(135deg, #1c202b 0%, #252a3a 100%);
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #2a2f3a;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .info-label {
            font-size: 14px;
            font-weight: 500;
            color: #8e9cb2;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 20px;
            padding: 8px 12px;
            background-color: rgba(52, 118, 255, 0.1);
            border-radius: 4px;
            border-left: 3px solid #3476ff;
            font-family: 'Courier New', monospace;
        }
        .info-value:last-child {
            margin-bottom: 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3476ff 0%, #4285f4 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(52, 118, 255, 0.3);
            transition: all 0.3s ease;
            text-align: center;
            min-width: 180px;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 118, 255, 0.4);
        }
        .footer {
            padding: 25px 30px;
            text-align: center;
            font-size: 13px;
            color: #8e9cb2;
            background: linear-gradient(135deg, #0f1219 0%, #13161d 100%);
            border-top: 1px solid #2a2f3a;
        }
        
        /* Responsive Design */
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px 5px;
            }
            .header {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .logo-text {
                font-size: 18px;
                letter-spacing: 1px;
            }
            .logo {
                height: 35px;
            }
            .content {
                padding: 25px 20px;
            }
            .content-wrapper {
                padding-left: 20px;
            }
            h2 {
                font-size: 22px;
            }
            p {
                font-size: 15px;
            }
            .account-info {
                padding: 20px;
                margin: 20px 0;
            }
            .cta-button {
                padding: 12px 25px;
                font-size: 15px;
                min-width: 160px;
            }
            .footer {
                padding: 20px;
                font-size: 12px;
            }
        }
        
        @media only screen and (max-width: 480px) {
            .header {
                padding: 15px;
            }
            .content {
                padding: 20px 15px;
            }
            .content-wrapper {
                padding-left: 15px;
            }
            .account-info {
                padding: 15px;
            }
            h2 {
                font-size: 20px;
            }
            .cta-button {
                width: 100%;
                text-align: center;
                box-sizing: border-box;
            }
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