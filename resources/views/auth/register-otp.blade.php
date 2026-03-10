<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>ERP · Verify Registration OTP</title>

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 10% 30%, #1a1a2e, #0d0d1a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 70% 60%, rgba(0, 212, 255, 0.08) 0%, transparent 45%);
            pointer-events: none;
            animation: pulseGlow 4s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path d="M10 50 Q 30 30, 50 50 T 90 50" stroke="%2300d4ff" fill="none" stroke-width="0.5"/><path d="M10 60 Q 30 80, 50 60 T 90 60" stroke="%2300d4ff" fill="none" stroke-width="0.5"/></svg>');
            background-size: cover;
            pointer-events: none;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Main Container */
        .otp-container {
            width: 100%;
            max-width: 460px;
            background: rgba(18, 18, 28, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 2px solid rgba(0, 212, 255, 0.3);
            border-radius: 32px;
            padding: 45px 35px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6),
                        0 0 0 1px rgba(0, 212, 255, 0.2),
                        0 0 30px rgba(0, 212, 255, 0.3);
            position: relative;
            z-index: 10;
            animation: slideUpFade 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        @keyframes slideUpFade {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Decorative Elements */
        .otp-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #00d4ff, transparent, #00d4ff);
            border-radius: 32px;
            z-index: -1;
            animation: borderRotate 4s linear infinite;
            opacity: 0.3;
        }

        @keyframes borderRotate {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h3 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff, #a5f3fc, #00d4ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .header h3 i {
            background: rgba(0, 212, 255, 0.15);
            padding: 12px;
            border-radius: 50%;
            font-size: 24px;
            color: #00d4ff;
            -webkit-text-fill-color: #00d4ff;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .subtitle {
            font-size: 14px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            padding: 12px 20px;
            border-radius: 50px;
            border: 1px solid rgba(0, 212, 255, 0.15);
            width: fit-content;
            margin: 0 auto;
        }

        .subtitle i {
            color: #00d4ff;
            animation: ring 2s ease-in-out infinite;
        }

        @keyframes ring {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(15deg); }
            20% { transform: rotate(-15deg); }
            30% { transform: rotate(10deg); }
            40% { transform: rotate(-10deg); }
            50% { transform: rotate(5deg); }
            60% { transform: rotate(-5deg); }
            100% { transform: rotate(0deg); }
        }

        /* Email Display */
        .email-display {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 100, 255, 0.05));
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 16px;
            padding: 18px;
            margin: 25px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 15px;
            color: #e2e8f0;
            word-break: break-all;
            backdrop-filter: blur(5px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.1);
            transition: all 0.3s ease;
        }

        .email-display:hover {
            border-color: #00d4ff;
            box-shadow: 0 5px 25px rgba(0, 212, 255, 0.2);
            transform: translateY(-2px);
        }

        .email-display i {
            color: #00d4ff;
            font-size: 20px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* OTP Input Field */
        .otp-field {
            position: relative;
            margin: 30px 0 20px;
        }

        .otp-input {
            width: 100%;
            height: 80px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid #334155;
            border-radius: 20px;
            padding: 0 20px;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 12px;
            text-align: center;
            color: #fff;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Courier New', monospace;
            caret-color: #00d4ff;
            box-shadow: inset 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .otp-input:focus {
            border-color: #00d4ff;
            background: rgba(0, 212, 255, 0.05);
            box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.15),
                        inset 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .otp-input::placeholder {
            font-size: 20px;
            letter-spacing: 4px;
            color: #4a5568;
            font-weight: 300;
        }

        /* Floating Label */
        .input-label {
            position: absolute;
            top: -12px;
            left: 20px;
            background: rgba(18, 18, 28, 0.95);
            padding: 0 10px;
            font-size: 13px;
            color: #00d4ff;
            font-weight: 500;
            backdrop-filter: blur(5px);
            border-radius: 20px;
            border: 1px solid rgba(0, 212, 255, 0.3);
        }

        /* Timer */
        .timer {
            text-align: center;
            font-size: 15px;
            color: #94a3b8;
            margin: 15px 0;
            padding: 10px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .timer i {
            color: #fbbf24;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .timer-warning {
            color: #fbbf24;
            font-weight: 600;
        }

        .timer-danger {
            color: #ef4444;
            font-weight: 600;
            animation: blink 1s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Verify Button */
        .btn-verify {
            width: 100%;
            height: 60px;
            background: linear-gradient(135deg, #00b4d8, #0077b6, #00b4d8);
            background-size: 200% 200%;
            border: none;
            border-radius: 40px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
            transition: all 0.4s ease;
            margin: 25px 0 15px;
            position: relative;
            overflow: hidden;
        }

        .btn-verify::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .btn-verify:hover {
            background-position: right center;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 212, 255, 0.5);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .btn-verify:hover::before {
            left: 100%;
        }

        .btn-verify:active {
            transform: translateY(0) scale(1);
        }

        .btn-verify i {
            font-size: 20px;
            animation: none;
        }

        .btn-verify:hover i {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            75% { transform: rotate(-10deg); }
        }

        /* Alert Messages */
        .alert {
            padding: 16px 20px;
            border-radius: 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border-left: 4px solid #ef4444;
            color: #fee2e2;
            backdrop-filter: blur(5px);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border-left: 4px solid #10b981;
            color: #d1fae5;
            backdrop-filter: blur(5px);
        }

        .alert i {
            font-size: 20px;
        }

        .alert-error i {
            color: #ef4444;
        }

        .alert-success i {
            color: #10b981;
        }

        /* Resend Section */
        .resend-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px dashed rgba(0, 212, 255, 0.2);
            position: relative;
        }

        .resend-section::before {
            content: '⏱️';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: #1a1a2e;
            padding: 0 15px;
            font-size: 20px;
        }

        .btn-resend {
            background: transparent;
            border: 2px solid rgba(0, 212, 255, 0.3);
            color: #94a3b8;
            font-size: 15px;
            font-weight: 500;
            padding: 14px 35px;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-resend::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(0, 212, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-resend:hover:not(:disabled) {
            border-color: #00d4ff;
            color: #fff;
            background: rgba(0, 212, 255, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.2);
        }

        .btn-resend:hover:not(:disabled)::before {
            width: 300px;
            height: 300px;
        }

        .btn-resend:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(0.5);
        }

        .btn-resend i {
            color: #00d4ff;
            transition: transform 0.3s ease;
        }

        .btn-resend:hover:not(:disabled) i {
            transform: rotate(180deg);
        }

        /* Attempts Counter */
        .attempts {
            text-align: center;
            font-size: 13px;
            color: #fbbf24;
            margin: 15px 0 5px;
            padding: 8px;
            background: rgba(251, 191, 36, 0.1);
            border-radius: 20px;
            display: inline-block;
            width: auto;
            margin-left: auto;
            margin-right: auto;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.02);
        }

        .back-link a:hover {
            color: #00d4ff;
            gap: 12px;
            background: rgba(0, 212, 255, 0.1);
        }

        .back-link i {
            transition: transform 0.3s ease;
        }

        .back-link a:hover i {
            transform: translateX(-5px);
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #4b5563;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .otp-container {
                padding: 35px 25px;
                border-radius: 24px;
            }

            .header h3 {
                font-size: 26px;
            }

            .header h3 i {
                font-size: 20px;
                padding: 10px;
            }

            .otp-input {
                height: 70px;
                font-size: 30px;
                letter-spacing: 8px;
            }

            .btn-verify {
                height: 55px;
                font-size: 16px;
            }

            .email-display {
                font-size: 13px;
                padding: 15px;
            }
        }

        @media (max-width: 360px) {
            .otp-container {
                padding: 25px 20px;
            }

            .header h3 {
                font-size: 22px;
            }

            .otp-input {
                letter-spacing: 6px;
                font-size: 26px;
            }

            .btn-resend {
                padding: 12px 25px;
                font-size: 14px;
            }
        }

        /* Loading Animation */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a2e;
        }

        ::-webkit-scrollbar-thumb {
            background: #00d4ff;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #00b8d4;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <!-- Header -->
        <div class="header">
            <h3>
                <i class="fas fa-shield-hal"></i>
                Verify OTP
            </h3>
            <div class="subtitle">
                <i class="fas fa-envelope"></i>
                <span>OTP sent to your email</span>
                @if(env('FAST2SMS_API_KEY'))
                    <i class="fas fa-mobile-alt" style="margin-left: 8px;"></i>
                    <span>& mobile</span>
                @endif
            </div>
        </div>

        <!-- Email Display -->
        @if(isset($email) && $email)
        <div class="email-display">
            <i class="fas fa-envelope-circle-check"></i>
            <span>{{ $email }}</span>
        </div>
        @endif

        <!-- Error Messages -->
        @error('otp')
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
        </div>
        @enderror

        <!-- Success Messages -->
        @if (session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        <!-- OTP Form -->
        <form method="POST" action="{{ route('register.otp.verify') }}" id="otpForm">
            @csrf

            <div class="otp-field">
                <span class="input-label">
                    <i class="fas fa-key" style="margin-right: 5px;"></i> 6-Digit OTP
                </span>
                <input type="text"
                       name="otp"
                       class="otp-input"
                       placeholder="• • • • • •"
                       maxlength="6"
                       pattern="[0-9]{6}"
                       inputmode="numeric"
                       autocomplete="off"
                       required
                       autofocus
                       id="otpInput">
            </div>

            <!-- Timer -->
            <div class="timer" id="timer">
                <i class="fas fa-hourglass-half"></i>
                <span id="timerText">05:00</span> remaining
            </div>

            <!-- Attempts Counter -->
            @if(isset($attempts_remaining) && $attempts_remaining < 5)
            <div class="attempts">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $attempts_remaining }} attempts remaining
            </div>
            @endif

            <button type="submit" class="btn-verify" id="verifyBtn">
                <i class="fas fa-check-circle"></i> Verify OTP
            </button>
        </form>

        <!-- Resend Section -->
        <div class="resend-section">
            <form method="POST" action="{{ route('register.otp.resend') }}" id="resendForm">
                @csrf
                <button type="submit" class="btn-resend" id="resendBtn">
                    <i class="fas fa-rotate-right"></i> Resend OTP
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <div class="back-link">
            <a href="{{ route('register') }}">
                <i class="fas fa-arrow-left"></i> Back to Registration
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <i class="fas fa-lock" style="color: #00d4ff80;"></i> Secure verification
        </div>
    </div>

    <script>
        // Timer functionality
        const timerElement = document.getElementById('timerText');
        const timerContainer = document.querySelector('.timer');
        const resendBtn = document.getElementById('resendBtn');
        const verifyBtn = document.getElementById('verifyBtn');
        const otpInput = document.getElementById('otpInput');

        // Set expiry time (5 minutes from now)
        let expiryTime;
        @if(session('otp_expires_at'))
            expiryTime = {{ session('otp_expires_at') }} * 1000;
        @else
            expiryTime = Date.now() + (5 * 60 * 1000);
        @endif

        function updateTimer() {
            const now = Date.now();
            const distance = expiryTime - now;

            if (distance <= 0) {
                timerElement.innerHTML = '00:00';
                timerContainer.classList.add('timer-danger');
                timerContainer.querySelector('i').className = 'fas fa-hourglass-end';

                // Disable verify button if OTP expired
                verifyBtn.disabled = true;
                verifyBtn.style.opacity = '0.5';
                verifyBtn.style.cursor = 'not-allowed';

                // Show expired message if not already shown
                if (!document.getElementById('expiredMsg')) {
                    const expiredMsg = document.createElement('div');
                    expiredMsg.id = 'expiredMsg';
                    expiredMsg.className = 'alert alert-error';
                    expiredMsg.innerHTML = '<i class="fas fa-clock"></i> OTP expired. Please request a new one.';
                    document.querySelector('.otp-field').after(expiredMsg);
                }
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timerElement.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            // Change color in last minute
            if (distance < 60000) {
                timerContainer.classList.add('timer-warning');
                timerContainer.classList.remove('timer-danger');
            } else {
                timerContainer.classList.remove('timer-warning', 'timer-danger');
            }
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // OTP input validation
        otpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }

            // Add animation on complete
            if (this.value.length === 6) {
                this.style.borderColor = '#10b981';
                this.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.15)';
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });

        // Focus animation
        otpInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        otpInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });

        // Resend cooldown
        @if(session('otp_sent_at'))
        let lastSent = {{ session('otp_sent_at') }};
        @else
        let lastSent = Math.floor(Date.now() / 1000) - 120; // 2 minutes ago for testing
        @endif

        function updateResendButton() {
            const now = Math.floor(Date.now() / 1000);
            const diff = now - lastSent;

            if (diff < 60) {
                const waitTime = 60 - diff;
                resendBtn.disabled = true;
                resendBtn.innerHTML = `<i class="fas fa-hourglass-half"></i> Wait ${waitTime}s`;

                setTimeout(updateResendButton, 1000);
            } else {
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="fas fa-rotate-right"></i> Resend OTP';
            }
        }

        updateResendButton();

        // Handle resend form
        document.getElementById('resendForm').addEventListener('submit', function(e) {
            if (resendBtn.disabled) {
                e.preventDefault();
                return;
            }

            resendBtn.disabled = true;
            resendBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Sending...';
        });

        // Handle main form submission
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Verifying...';
        });

        // Auto paste functionality
        otpInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = pastedText.replace(/[^0-9]/g, '').slice(0, 6);
            this.value = numbers;

            // Trigger input event
            const inputEvent = new Event('input', { bubbles: true });
            this.dispatchEvent(inputEvent);
        });

        // Add ripple effect to buttons
        function createRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.className = 'ripple';

            button.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        // Add ripple styles
        const style = document.createElement('style');
        style.textContent = `
            .btn-verify, .btn-resend {
                position: relative;
                overflow: hidden;
            }
            .ripple {
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
            }
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Add ripple to buttons
        document.querySelectorAll('.btn-verify, .btn-resend').forEach(btn => {
            btn.addEventListener('click', createRipple);
        });
    </script>
</body>
</html>
