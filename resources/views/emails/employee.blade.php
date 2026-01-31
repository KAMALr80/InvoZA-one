<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'Email from ' . config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 32px;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .content {
            padding: 40px;
        }

        .message {
            font-size: 16px;
            line-height: 1.7;
            color: #4b5563;
            white-space: pre-line;
            margin-bottom: 32px;
        }

        .signature {
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            margin-top: 32px;
        }

        .sender-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sender-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
        }

        .sender-details h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .sender-details p {
            color: #6b7280;
            font-size: 14px;
        }

        .footer {
            background: #f3f4f6;
            padding: 24px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }

        .footer p {
            margin: 4px 0;
        }

        .company-name {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Employee Communication</p>
        </div>

        <div class="content">
            <div class="message">
                {!! nl2br(e($body ?? '')) !!}
            </div>

            <div class="signature">
                <div class="sender-info">
                    @if ($sender)
                        <div class="sender-avatar">
                            {{ strtoupper(substr($sender->name, 0, 1)) }}
                        </div>
                        <div class="sender-details">
                            <h3>{{ $sender->name }}</h3>
                            <p>{{ ucfirst($sender->role ?? 'Team Member') }} • {{ config('app.name') }}</p>
                        </div>
                    @else
                        <div class="sender-avatar">
                            {{ strtoupper(substr(config('app.name'), 0, 1)) }}
                        </div>
                        <div class="sender-details">
                            <h3>{{ config('app.name') }} Team</h3>
                            <p>Official Communication</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer">
            <p class="company-name">{{ config('app.name') }}</p>
            <p>This is an automated email from our employee management system.</p>
            <p>Please do not reply to this email directly.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
