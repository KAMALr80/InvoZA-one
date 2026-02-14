<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>ERP · secure login</title>
    <!-- Poppins & clean base — exactly your provided CSS, merged & no old inline styles -->
    <style>
        /* ------------------------------------------------------------
           FULL MERGE : YOUR EXACT CSS (improved + dark cyber theme)
           All old inline styles removed, now pure .auth-wrapper magic
           --- REGISTER PANEL REMOVED, SIGN UP REDIRECTS TO SEPARATE PAGE ---
        ------------------------------------------------------------ */
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

        /* ---------- MAIN AUTH WRAPPER –– your complete design ---------- */
        .auth-wrapper {
            position: relative;
            width: 100%;
            max-width: 800px;
            height: 500px;
            border: 2px solid #00d4ff;
            box-shadow: 0 0 25px #00d4ff;
            overflow: hidden;
            background: #1a1a2e;
            /* deep base */
        }

        /* both credential panels — signup panel removed from DOM, only signin remains */
        .auth-wrapper .credentials-panel {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        /* ----- SIGN IN (left) ----- */
        .credentials-panel.signin {
            left: 0;
            padding: 0 40px;
            width: 50%;
        }

        .credentials-panel.signin .slide-element {
            transform: translateX(0%);
            transition: .7s;
            opacity: 1;
        }

        /* no toggled delays needed anymore – we remove toggled completely */
        .credentials-panel.signin .slide-element:nth-child(1) {
            transition-delay: 0.1s;
        }

        .credentials-panel.signin .slide-element:nth-child(2) {
            transition-delay: 0.2s;
        }

        .credentials-panel.signin .slide-element:nth-child(3) {
            transition-delay: 0.3s;
        }

        .credentials-panel.signin .slide-element:nth-child(4) {
            transition-delay: 0.4s;
        }

        .credentials-panel.signin .slide-element:nth-child(5) {
            transition-delay: 0.5s;
        }

        /* remove all .toggled references – not needed */
        .auth-wrapper.toggled .credentials-panel.signin .slide-element {
            /* no toggling, register is separate page */
        }

        /* headings */
        .credentials-panel h2 {
            font-size: 32px;
            text-align: center;
        }

        /* stylish field wrapper */
        .credentials-panel .field-wrapper {
            position: relative;
            width: 100%;
            height: 50px;
            margin-top: 25px;
        }

        .field-wrapper input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-right: 23px;
            transition: .5s;
        }

        .field-wrapper input:focus,
        .field-wrapper input:valid {
            border-bottom: 2px solid #00d4ff;
        }

        .field-wrapper label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            font-size: 16px;
            color: #fff;
            transition: .5s;
        }

        .field-wrapper input:focus~label,
        .field-wrapper input:valid~label {
            top: -5px;
            color: #00d4ff;
        }

        .field-wrapper i {
            position: absolute;
            top: 50%;
            right: 0;
            font-size: 18px;
            transform: translateY(-50%);
            color: #fff;
        }

        .field-wrapper input:focus~i,
        .field-wrapper input:valid~i {
            color: #00d4ff;
        }

        /* neon submit button */
        .submit-button {
            position: relative;
            width: 100%;
            height: 45px;
            background: transparent;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            border: 2px solid #00d4ff;
            overflow: hidden;
            z-index: 1;
            color: white;
            margin-top: 30px;
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

        .switch-link {
            font-size: 14px;
            text-align: center;
            margin: 20px 0 10px;
        }

        .switch-link a {
            text-decoration: none;
            color: #00d4ff;
            font-weight: 600;
            transition: .3s;
        }

        .switch-link a:hover {
            text-decoration: underline;
            color: #00b8d4;
        }

        /* ---------- WELCOME section (right side) only signin visible ---------- */
        .welcome-section {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .welcome-section.signin {
            right: 0;
            text-align: right;
            padding: 0 40px 60px 150px;
        }

        .welcome-section.signin .slide-element {
            transform: translateX(0);
            transition: .7s ease;
            opacity: 1;
            filter: blur(0px);
        }

        .welcome-section.signin .slide-element:nth-child(1) {
            transition-delay: 0.2s;
        }

        .welcome-section.signin .slide-element:nth-child(2) {
            transition-delay: 0.3s;
        }

        /* hide signup welcome section completely */
        .welcome-section.signup {
            display: none;
        }

        .welcome-section h2 {
            text-transform: uppercase;
            font-size: 36px;
            line-height: 1.3;
        }

        .welcome-section p {
            font-size: 16px;
        }

        /* ---------- iconic shape animations (static, no toggle) ---------- */
        .auth-wrapper .background-shape {
            position: absolute;
            right: 0;
            top: -5px;
            height: 600px;
            width: 850px;
            background: linear-gradient(45deg, #1a1a2e, #00d4ff);
            transform: rotate(10deg) skewY(40deg);
            transform-origin: bottom right;
            transition: 1.5s ease;
            transition-delay: 0.2s;
        }

        .auth-wrapper .secondary-shape {
            position: absolute;
            left: 250px;
            top: 100%;
            height: 700px;
            width: 850px;
            background: #1a1a2e;
            border-top: 3px solid #00d4ff;
            transform: rotate(0deg) skewY(0deg);
            transform-origin: bottom left;
            transition: 1.5s ease;
            transition-delay: 0.2s;
        }

        /* ----- hidden lat/lng (geolocation) ----- */
        input[type="hidden"] {
            display: none;
        }

        /* ----- Mobile responsive (adjusted) ----- */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .footer {
                margin-top: 20px;
                font-size: 13px;
            }

            .auth-wrapper {
                height: auto;
                min-height: 500px;
                flex-direction: column;
            }

            .auth-wrapper .credentials-panel,
            .welcome-section {
                width: 100%;
                position: relative;
            }

            .credentials-panel.signin {
                padding: 40px 30px;
                left: 0;
                right: 0;
                width: 100%;
                display: flex;
                animation: fadeInUp 0.6s ease forwards;
            }

            .credentials-panel.signin .slide-element {
                transform: translateY(0);
                opacity: 1;
                filter: blur(0);
                animation: slideInUp 0.5s ease forwards;
            }

            .credentials-panel.signin .slide-element:nth-child(1) {
                animation-delay: 0.1s;
                opacity: 0;
            }

            .credentials-panel.signin .slide-element:nth-child(2) {
                animation-delay: 0.2s;
                opacity: 0;
            }

            .credentials-panel.signin .slide-element:nth-child(3) {
                animation-delay: 0.3s;
                opacity: 0;
            }

            .credentials-panel.signin .slide-element:nth-child(4) {
                animation-delay: 0.4s;
                opacity: 0;
            }

            .credentials-panel.signin .slide-element:nth-child(5) {
                animation-delay: 0.5s;
                opacity: 0;
            }

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

            .welcome-section {
                display: none;
                /* hide on mobile */
            }

            .credentials-panel h2 {
                font-size: 28px;
                margin-bottom: 10px;
            }

            .auth-wrapper .background-shape,
            .auth-wrapper .secondary-shape {
                display: none;
            }

            .field-wrapper {
                margin-top: 20px;
            }
        }

        @media (max-width: 480px) {
            .credentials-panel.signin {
                padding: 30px 20px;
            }

            .credentials-panel h2 {
                font-size: 24px;
            }

            .field-wrapper input,
            .field-wrapper label {
                font-size: 14px;
            }

            .submit-button {
                font-size: 14px;
                height: 40px;
            }

            .switch-link {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <!--
        ========================================================
        LOGIN PAGE – REGISTER PANEL REMOVED
        "Sign up" now opens the actual register page (separate).
        No inline styles, pure cyber CSS.
        ========================================================
    -->
    <div class="auth-wrapper" id="authWrapper">
        <!-- background shapes (cyber effect) -->
        <div class="background-shape"></div>
        <div class="secondary-shape"></div>

        <!-- SIGN IN PANEL (left) - credentials only -->
        <div class="credentials-panel signin">
            <h2 class="slide-element">🔐 ERP Login</h2>
            <p class="slide-element" style="font-size:13px; color:#b0c4de; margin-bottom:5px; text-align:center;">
                Sign in to access your dashboard
            </p>

            <!-- Laravel session status placeholder -->
            <div class="slide-element" style="margin-bottom:5px;">
                <!-- <x-auth-session-status class="mb-4" :status="session('status')" /> -->
            </div>

            <form method="POST" action="{{ route('login') }}" style="width:100%;">
                @csrf

                <!-- EMAIL -->
                <div class="field-wrapper slide-element">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus id="email">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope"></i>
                </div>
                <!-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> -->

                <!-- PASSWORD -->
                <div class="field-wrapper slide-element">
                    <input type="password" name="password" required id="password">
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                </div>
                <!-- <x-input-error :messages="$errors->get('password')" class="mt-2" /> -->

                <!-- REMEMBER + FORGOT -->
                <div class="slide-element"
                    style="display:flex; justify-content:space-between; align-items:center; margin-top:18px;">
                    <label style="display:flex; align-items:center; font-size:13px; color:#ccc;">
                        <input type="checkbox" name="remember" style="margin-right:6px; accent-color:#00d4ff;">
                        <span style="color:#ddd;">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            style="font-size:13px; color:#00d4ff; text-decoration:none; font-weight:500;">
                            Forgot?
                        </a>
                    @endif
                </div>

                <!-- LOGIN BUTTON -->
                <button type="submit" class="submit-button slide-element">
                    LOGIN
                </button>

                <!-- hidden geo fields -->
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
            </form>

            <!-- SWITCH TO SIGN UP - now redirects to external register page (no toggle) -->
            <div class="switch-link slide-element">
                Don't have account?
                <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>

        <!-- WELCOME section - SIGN IN side (right side) -->
        <div class="welcome-section signin">
            <h2 class="slide-element">Welcome Back!</h2>
            <p class="slide-element">Securely access your ERP dashboard with modern protection.</p>
        </div>

        <!-- SIGN UP WELCOME SECTION REMOVED -->
    </div>

    <!-- footer as per your design -->
    <div class="footer">
        <span>© 2025 ERP System — </span>
        <a href="#">Enterprise</a>
        <span> · secure zone</span>
    </div>

    <!-- Font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Geolocation script (unchanged) -->
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('lng').value = pos.coords.longitude;
                },
                function() {
                    alert('Location allow karna zaruri hai for secure login');
                }
            );
        }
    </script>

    {{-- Blade directives work: route('login'), route('register'), route('password.request'), @csrf, old('email') --}}
</body>

</html>
