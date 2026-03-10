<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP OTP Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 30px auto;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 1px solid #00d4ff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            color: #00d4ff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .greeting {
            color: #fff;
            font-size: 18px;
            margin-bottom: 15px;
            text-align: center;
        }
        .otp-box {
            background: rgba(0, 212, 255, 0.1);
            border: 2px solid #00d4ff;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .otp-code {
            font-size: 52px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #fff;
            text-shadow: 0 0 15px #00d4ff;
            font-family: 'Courier New', monospace;
        }
        .purpose {
            color: #00d4ff;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .details {
            color: #b0c4de;
            font-size: 14px;
            line-height: 1.8;
            text-align: center;
            margin: 20px 0;
        }
        .expiry {
            background: rgba(255, 255, 255, 0.05);
            padding: 12px;
            border-radius: 8px;
            color: #fbbf24;
            font-size: 14px;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #334155;
            color: #94a3b8;
            font-size: 12px;
        }
        .warning {
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            padding: 12px 15px;
            margin-top: 20px;
            font-size: 13px;
            color: #fecaca;
            border-radius: 4px;
        }
        .warning i {
            color: #ef4444;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔐 ERP {{ $purpose }} OTP</h2>
        </div>

        <div class="greeting">
            Hello {{ $name ?? 'User' }},
        </div>

        <div class="details">
            <p>You requested an OTP for <strong>{{ $purpose }}</strong> verification.</p>
        </div>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
            <div class="purpose">{{ $purpose }} Verification</div>
        </div>

        <div class="expiry">
            ⏰ This OTP will expire in <strong>5 minutes</strong>
        </div>

        <div class="warning">
            <i>⚠️</i> Never share this OTP with anyone. Our team will never ask for your OTP.
        </div>

        <div class="details">
            <p>If you didn't request this OTP, please ignore this email.</p>
            <p>Your account security is important to us.</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} ERP System. All rights reserved.</p>
            <p>Secure enterprise portal with location-based access</p>
        </div>
    </div>
</body>
</html>
