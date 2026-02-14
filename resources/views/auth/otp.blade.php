<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>ERP · Verify OTP</title>

    <!-- Poppins Font (same as login/register) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* ====================================================================
           ERP OTP VERIFICATION PAGE – DARK CYBER THEME
           Fully merged with your login/register design system
           Neon accents, glass morphism, responsive
           ==================================================================== */

        /* ----------------------------
           CSS RESET & GLOBAL STYLES
        ---------------------------- */
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
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #fff;
            position: relative;
        }

        /* subtle animated background */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 70% 60%, rgba(0, 212, 255, 0.04) 0%, transparent 45%);
            pointer-events: none;
        }

        /* ----------------------------
           MAIN OTP CARD – NEON GLASS
        ---------------------------- */
        .otp-container {
            width: 100%;
            max-width: 420px;
            background: rgba(18, 18, 28, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(0, 212, 255, 0.25);
            border-radius: 28px;
            padding: 45px 35px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(0, 212, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
            animation: fadeScale 0.5s ease-out;
        }

        .otp-container:hover {
            border-color: rgba(0, 212, 255, 0.5);
            box-shadow: 0 30px 60px rgba(0, 212, 255, 0.2), 0 0 0 1px rgba(0, 212, 255, 0.3);
        }

        @keyframes fadeScale {
            0% { opacity: 0; transform: scale(0.96); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* ----------------------------
           HEADER SECTION
        ---------------------------- */
        .otp-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .otp-header h3 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, #ffffff, #a5f3fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .otp-header h3 i {
            background: rgba(0, 212, 255, 0.15);
            padding: 8px;
            border-radius: 50%;
            font-size: 22px;
            color: #00d4ff;
            -webkit-text-fill-color: #00d4ff;
        }

        .otp-subhead {
            font-size: 14px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.02);
            padding: 10px 18px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            width: fit-content;
            margin: 0 auto;
        }

        .otp-subhead i {
            color: #00d4ff;
        }

        /* ----------------------------
           OTP INPUT FIELD – SPECIAL STYLE
        ---------------------------- */
        .otp-field {
            position: relative;
            margin: 35px 0 20px;
        }

        .otp-input {
            width: 100%;
            height: 70px;
            background: rgba(10, 10, 20, 0.7);
            border: 2px solid #334155;
            border-radius: 16px;
            padding: 0 20px;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 12px;
            text-align: center;
            color: #fff;
            outline: none;
            transition: all 0.25s ease;
            font-family: 'Poppins', monospace;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .otp-input:focus {
            border-color: #00d4ff;
            background: rgba(0, 212, 255, 0.05);
            box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.1), inset 0 2px 8px rgba(0, 0, 0, 0.5);
        }

        .otp-input::placeholder {
            font-size: 16px;
            letter-spacing: 2px;
            color: #6b7280;
            font-weight: 300;
        }

        /* label style */
        .otp-label {
            display: block;
            text-align: left;
            font-size: 13px;
            color: #cbd5e1;
            margin-bottom: 8px;
            font-weight: 500;
            margin-left: 6px;
        }

        .otp-label i {
            color: #00d4ff;
            margin-right: 6px;
        }

        /* ----------------------------
           NEON VERIFY BUTTON
        ---------------------------- */
        .btn-neon {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            border: none;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.25);
            transition: all 0.3s ease;
            margin-top: 25px;
            position: relative;
            overflow: hidden;
        }

        .btn-neon:hover {
            background: linear-gradient(135deg, #00c8ff, #0088cc);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 212, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .btn-neon:active {
            transform: translateY(1px);
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.2);
        }

        /* ----------------------------
           MESSAGE ALERTS (SUCCESS / ERROR)
        ---------------------------- */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            background: rgba(0, 0, 0, 0.3);
            border-left: 5px solid;
            backdrop-filter: blur(4px);
        }

        .alert-success {
            border-left-color: #10b981;
            background: rgba(16, 185, 129, 0.08);
            color: #d1fae5;
        }

        .alert-error {
            border-left-color: #ef4444;
            background: rgba(239, 68, 68, 0.08);
            color: #fee2e2;
        }

        .alert i {
            font-size: 18px;
        }

        .alert-success i {
            color: #10b981;
        }

        .alert-error i {
            color: #ef4444;
        }

        /* ----------------------------
           RESEND OTP – LINK STYLE
        ---------------------------- */
        .resend-section {
            margin-top: 25px;
            text-align: center;
            border-top: 1px dashed rgba(255, 255, 255, 0.1);
            padding-top: 22px;
        }

        .resend-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.25s;
            padding: 10px 20px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .resend-btn i {
            color: #00d4ff;
            transition: 0.25s;
        }

        .resend-btn:hover {
            background: rgba(0, 212, 255, 0.05);
            border-color: rgba(0, 212, 255, 0.3);
            color: #fff;
        }

        .resend-btn:hover i {
            transform: rotate(20deg);
            color: #7ee8ff;
        }

        .resend-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ----------------------------
           FOOTER (optional consistency)
        ---------------------------- */
        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            z-index: 20;
        }

        .footer a {
            color: #00d4ff;
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
            color: #7ee8ff;
        }

        /* ----------------------------
           RESPONSIVE
        ---------------------------- */
        @media (max-width: 480px) {
            .otp-container {
                padding: 35px 25px;
                border-radius: 24px;
            }

            .otp-header h3 {
                font-size: 24px;
            }

            .otp-input {
                height: 60px;
                font-size: 24px;
                letter-spacing: 8px;
            }

            .btn-neon {
                height: 52px;
                font-size: 15px;
            }
        }

        @media (max-width: 360px) {
            .otp-container {
                padding: 30px 20px;
            }

            .otp-header h3 {
                font-size: 22px;
            }

            .otp-input {
                letter-spacing: 6px;
            }
        }

        /* hidden fields */
        input[type="hidden"] {
            display: none;
        }
    </style>
</head>
<body>

    <!--
        ========================================================
        ERP OTP VERIFICATION PAGE – FULLY MERGED CSS
        Dark theme, neon accents, glass card, responsive
        Preserves all Laravel Blade directives
        ========================================================
    -->

    <div class="otp-container">

        <!-- HEADER with icon -->
        <div class="otp-header">
            <h3>
                <i class="fas fa-shield-hal"></i> Verify Login OTP
            </h3>
            <div class="otp-subhead">
                <i class="fas fa-envelope"></i> OTP sent to your email
            </div>
        </div>

        <!-- OTP VERIFICATION FORM -->
        <form method="POST" action="{{ route('otp.verify.post') }}">
            @csrf

            <!-- OTP Label & Input (6 digit) -->
            <div class="otp-field">
                <div class="otp-label">
                    <i class="fas fa-key"></i> Enter 6-digit OTP
                </div>
                <input type="text"
                       name="otp"
                       inputmode="numeric"
                       pattern="[0-9]{6}"
                       maxlength="6"
                       required
                       placeholder="• • • • • •"
                       class="otp-input"
                       autofocus>
            </div>

            <!-- ERROR DISPLAY (blade) -->
            @error('otp')
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <!-- VERIFY BUTTON (neon) -->
            <button type="submit" class="btn-neon">
                <i class="fas fa-check-circle"></i> Verify OTP
            </button>
        </form>

        <!-- SESSION STATUS (success) -->
        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- ANY ERROR (fallback) -->
        @if ($errors->any() && !$errors->has('otp'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- RESEND OTP SECTION -->
        <div class="resend-section">
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
                @csrf
                <button type="submit"
                        onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-pulse\'></i> Sending...'; this.form.submit();"
                        class="resend-btn">
                    <i class="fas fa-rotate-right"></i> Resend OTP
                </button>
            </form>
        </div>

        <!-- subtle footer inside card (optional) -->
        <div style="margin-top: 20px; text-align: center; font-size: 12px; color: #6b7280;">
            <i class="fas fa-lock" style="color: #00d4ff80;"></i> Secure verification
        </div>
    </div>

    <!-- FOOTER (global) -->
    <div class="footer">
        © 2025 ERP System — <a href="{{ route('login') }}">Back to Login</a> · OTP secure
    </div>

    <!-- Auto-focus & numeric keyboard on mobile -->
    <script>
        // force numeric keyboard on mobile
        const otpInput = document.querySelector('.otp-input');
        if (otpInput) {
            otpInput.addEventListener('focus', function() {
                this.setAttribute('inputmode', 'numeric');
            });

            // auto-submit on 6 digits? optional, but we keep manual submit
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
                }
            });
        }

        // handle resend button disabled state properly
        const resendForm = document.getElementById('resendForm');
        if (resendForm) {
            resendForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn.disabled) {
                    e.preventDefault();
                }
            });
        }
    </script>

    {{-- <!--
        LARAVEL BLADE DIRECTIVES ACTIVE:
        - {{ route('otp.verify.post') }}, {{ route('otp.resend') }}, {{ route('login') }}
        - @csrf, @error, session('status'), $errors, etc.
    --> --}}

</body>
</html>
