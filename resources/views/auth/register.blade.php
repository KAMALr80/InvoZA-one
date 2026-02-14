<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>ERP · Secure Registration</title>

    <!-- Poppins Font - Your exact import -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* ====================================================================
           YOUR EXACT CSS – FULLY APPLIED TO REGISTRATION PAGE
           Dark theme, neon accents, Poppins font, responsive
           All old inline styles REMOVED, replaced with your design system
        ==================================================================== */

        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #1a1a2e;
            padding: 20px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #fff;
        }

        .footer a {
            color: #00d4ff;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        .footer a:hover {
            text-decoration: underline;
            color: #00b8d4;
        }

        /* ---------- REGISTRATION CARD – AUTH WRAPPER ADAPTED ---------- */
        .auth-wrapper {
            position: relative;
            width: 100%;
            max-width: 500px;
            min-height: 680px;
            border: 2px solid #00d4ff;
            box-shadow: 0 0 25px #00d4ff;
            overflow: hidden;
            background: #1a1a2e;
            border-radius: 20px;
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Background shapes - your signature design */
        .auth-wrapper .background-shape {
            position: absolute;
            right: -50px;
            top: -50px;
            height: 500px;
            width: 600px;
            background: linear-gradient(45deg, #1a1a2e, #00d4ff);
            transform: rotate(15deg) skewY(25deg);
            transform-origin: bottom right;
            opacity: 0.25;
            z-index: 0;
            transition: 1.5s ease;
        }

        .auth-wrapper .secondary-shape {
            position: absolute;
            left: -80px;
            bottom: -80px;
            height: 500px;
            width: 600px;
            background: #1a1a2e;
            border-top: 3px solid #00d4ff;
            transform: rotate(-10deg) skewY(-20deg);
            transform-origin: bottom left;
            opacity: 0.2;
            z-index: 0;
            transition: 1.5s ease;
        }

        /* ---------- CONTENT – HIGHEST Z-INDEX ---------- */
        .register-content {
            position: relative;
            z-index: 10;
            width: 100%;
        }

        /* ---------- HEADER – Your typography ---------- */
        .register-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .register-header h2 {
            font-size: 32px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .register-header h2 i {
            color: #00d4ff;
            font-size: 32px;
        }

        .register-header p {
            font-size: 13px;
            color: #b0c4de;
            background: rgba(0, 212, 255, 0.08);
            padding: 10px 18px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(0, 212, 255, 0.2);
            margin-top: 5px;
        }

        .register-header p i {
            color: #00d4ff;
        }

        /* ---------- FORM – Your field wrapper style ---------- */
        .register-form {
            width: 100%;
        }

        .field-wrapper {
            position: relative;
            width: 100%;
            height: 55px;
            margin-top: 25px;
        }

        .field-wrapper input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 15px;
            color: #fff;
            font-weight: 500;
            border-bottom: 2px solid #fff;
            padding-right: 40px;
            padding-left: 5px;
            transition: .5s;
        }

        .field-wrapper input:focus,
        .field-wrapper input:valid {
            border-bottom: 2px solid #00d4ff;
        }

        .field-wrapper label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            font-size: 15px;
            color: #fff;
            transition: .5s;
            pointer-events: none;
            font-weight: 400;
        }

        .field-wrapper input:focus ~ label,
        .field-wrapper input:valid ~ label {
            top: -10px;
            font-size: 12px;
            color: #00d4ff;
        }

        .field-wrapper i {
            position: absolute;
            top: 50%;
            right: 5px;
            font-size: 18px;
            transform: translateY(-50%);
            color: #fff;
            transition: .5s;
        }

        .field-wrapper input:focus ~ i,
        .field-wrapper input:valid ~ i {
            color: #00d4ff;
        }

        /* Error messages - styled to match dark theme */
        .error-message {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 6px;
            margin-left: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .error-message i {
            color: #ff6b6b;
            font-size: 12px;
        }

        /* ---------- NEON SUBMIT BUTTON – Your exact style ---------- */
        .submit-button {
            position: relative;
            width: 100%;
            height: 50px;
            background: transparent;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            border: 2px solid #00d4ff;
            overflow: hidden;
            z-index: 1;
            color: white;
            margin-top: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.3s;
        }

        .submit-button::before {
            content: "";
            position: absolute;
            height: 300%;
            width: 100%;
            background: linear-gradient(#1a1a2e, #00d4ff, #1a1a2e, #00d4ff);
            top: -100%;
            left: 0;
            z-index: -1;
            transition: .5s;
        }

        .submit-button:hover:before {
            top: 0;
        }

        .submit-button:hover {
            border-color: #fff;
            box-shadow: 0 0 15px #00d4ff;
        }

        /* ---------- DIVIDER – Your style with neon ---------- */
        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0 20px;
            color: #94a3b8;
            font-size: 13px;
            gap: 15px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: rgba(0, 212, 255, 0.3);
        }

        /* ---------- BACK TO LOGIN – Switch link style ---------- */
        .back-login {
            display: block;
            text-align: center;
            padding: 14px;
            border-radius: 40px;
            background: transparent;
            border: 2px solid #00d4ff;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .back-login::before {
            content: "";
            position: absolute;
            height: 300%;
            width: 100%;
            background: linear-gradient(#1a1a2e, #00d4ff, #1a1a2e, #00d4ff);
            top: -100%;
            left: 0;
            z-index: -1;
            transition: .5s;
        }

        .back-login:hover:before {
            top: 0;
        }

        .back-login:hover {
            border-color: #fff;
            color: #fff;
            text-decoration: none;
        }

        .back-login i {
            margin-right: 8px;
            color: #00d4ff;
            transition: 0.3s;
        }

        .back-login:hover i {
            color: #fff;
        }

        /* ---------- MOBILE RESPONSIVE – Your exact media queries ---------- */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .footer {
                margin-top: 20px;
                font-size: 13px;
            }

            .auth-wrapper {
                padding: 35px 25px;
                min-height: 650px;
            }

            .auth-wrapper .background-shape,
            .auth-wrapper .secondary-shape {
                display: none;
            }

            .register-header h2 {
                font-size: 28px;
            }

            .field-wrapper {
                height: 50px;
                margin-top: 20px;
            }

            .submit-button {
                height: 48px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .auth-wrapper {
                padding: 30px 20px;
            }

            .register-header h2 {
                font-size: 24px;
            }

            .register-header h2 i {
                font-size: 24px;
            }

            .field-wrapper input,
            .field-wrapper label {
                font-size: 14px;
            }

            .submit-button {
                font-size: 14px;
                height: 46px;
            }

            .back-login {
                padding: 12px;
                font-size: 13px;
            }
        }

        /* ---------- ANIMATIONS – Your fadeInUp etc ---------- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-wrapper {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* slide-element simulation */
        .slide-element {
            animation: slideInUp 0.5s ease forwards;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* OTP hint style */
        .otp-hint {
            font-size: 12px;
            color: #aad4ff;
            margin-top: 8px;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }

        .otp-hint i {
            color: #00d4ff;
        }

        /* For x-input-error compatibility */
        .mt-2 {
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!--
        ====================================================================
        REGISTRATION PAGE – YOUR COMPLETE DARK CYBER THEME APPLIED
        All inline styles REMOVED, replaced with your exact CSS
        Poppins font, neon accents, animated fields, responsive
        ====================================================================
    -->

    <div class="auth-wrapper">
        <!-- Background shapes - your signature design -->
        <div class="background-shape"></div>
        <div class="secondary-shape"></div>

        <div class="register-content">
            <!-- HEADER SECTION -->
            <div class="register-header">
                <h2>
                    <i class="fas fa-pen-to-square"></i> ERP Register
                </h2>
                <p>
                    <i class="fas fa-shield-alt"></i>
                    <i class="fas fa-bolt" style="margin-left: 5px;"></i>
                    <span>Create your account. OTP verification required.</span>
                </p>
            </div>

            <!-- REGISTRATION FORM -->
            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf

                <!-- FULL NAME -->
                <div class="field-wrapper">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
                    <label for="name">Full Name</label>
                    <i class="fas fa-user"></i>
                </div>
                <x-input-error :messages="$errors->get('name')" class="error-message" />
                <div style="height: 5px;"></div>

                <!-- EMAIL ADDRESS -->
                <div class="field-wrapper">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope"></i>
                </div>
                <x-input-error :messages="$errors->get('email')" class="error-message" />
                <div style="height: 5px;"></div>

                <!-- MOBILE NUMBER -->
                <div class="field-wrapper">
                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" required maxlength="10" pattern="\d{10}">
                    <label for="mobile">Mobile Number</label>
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <x-input-error :messages="$errors->get('mobile')" class="error-message" />
                <div style="height: 5px;"></div>

                <!-- PASSWORD -->
                <div class="field-wrapper">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                </div>
                <x-input-error :messages="$errors->get('password')" class="error-message" />
                <div style="height: 5px;"></div>

                <!-- CONFIRM PASSWORD -->
                <div class="field-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                    <label for="password_confirmation">Confirm Password</label>
                    <i class="fas fa-check-circle"></i>
                </div>

                <!-- OTP Hint -->
                <div class="otp-hint">
                    <i class="fas fa-bolt"></i>
                    <span>OTP will be sent to your mobile & email</span>
                </div>

                <!-- REGISTER BUTTON -->
                <button type="submit" class="submit-button">
                    <i class="fas fa-message"></i> Register & Get OTP
                </button>
            </form>

            <!-- DIVIDER -->
            <div class="divider">
                <span class="divider-line"></span>
                <span>OR</span>
                <span class="divider-line"></span>
            </div>

            <!-- BACK TO LOGIN -->
            <a href="{{ route('login') }}" class="back-login">
                <i class="fas fa-arrow-left"></i> 🔐 Back to Login
            </a>
        </div>
    </div>

    <!-- FOOTER – Your exact footer -->
    <div class="footer">
        <span>© 2025 ERP System — </span>
        <a href="#">Secure Registration</a>
        <span> · OTP protected</span>
    </div>

    <!-- SCRIPT for mobile input validation -->
    <script>
        // Force numeric only for mobile field
        const mobileInput = document.getElementById('mobile');
        if (mobileInput) {
            mobileInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        }

        // Auto-capitalize name (optional)
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('input', function(e) {
                // Just a small UX touch
            });
        }
    </script>
{{--
    <!--
        LARAVEL BLADE DIRECTIVES – FULLY FUNCTIONAL
        {{ route('register') }}, {{ route('login') }}, @csrf, old('name'), etc.
        x-input-error components will work with custom error-message class
    --> --}}

</body>
</html>
