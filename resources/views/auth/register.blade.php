<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - SiProjek</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">

    <style>
        /* CSS reset & variables */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
        }
        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        /* Left Panel */
        .left-panel {
            background-color: #b5dadb; /* Soft light teal background matching mockup */
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 3rem;
            position: relative;
            overflow: hidden;
            height: 100vh;
        }
        .solustek-logo-container {
            margin-bottom: 2rem;
        }
        .solustek-logo {
            max-width: 280px;
            height: auto;
        }
        .illustration-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            margin-bottom: 2rem;
        }
        .working-illustration {
            width: 135%;
            max-width: none;
            height: auto;
            object-fit: contain;
            transform: scale(1.35);
        }
        /* Right Panel */
        .right-panel {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background-color: #ffffff;
            height: 100vh;
            overflow-y: auto;
        }
        .form-wrapper {
            width: 100%;
            max-width: 520px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 1rem;
        }
        .sp-logo-container {
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
        }
        .sp-logo {
            height: clamp(65px, 8vh, 85px);
            width: auto;
            object-fit: contain;
        }
        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 3.2vw, 2.6rem);
            font-weight: 800;
            color: #000000;
            text-align: center;
            line-height: 1.1;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }
        .welcome-subtitle {
            font-family: 'Playfair Display', serif;
            font-size: clamp(0.85rem, 1.2vw, 0.95rem);
            font-weight: 700;
            color: #000000;
            text-align: center;
            text-decoration: underline;
            margin-bottom: clamp(1rem, 2.5vh, 1.5rem);
            text-underline-offset: 4px;
            line-height: 1.4;
        }
        .login-form {
            width: 100%;
            max-width: 460px;
        }
        .input-group {
            margin-bottom: 1.1rem;
            display: flex;
            flex-direction: column;
        }
        .input-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 0.4rem;
        }
        .input-field {
            background-color: #e2e8f0; /* Soft light gray input background */
            border: none;
            outline: none;
            border-radius: 24px; /* Rounded pill shape inputs matching mockup */
            padding: 0.95rem 1.25rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: #334155;
            width: 100%;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 2px rgba(171, 205, 210, 0.6);
            background-color: #e8edf2;
        }
        .input-field::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 1.6rem;
            margin-bottom: 1rem;
        }
        .login-button {
            background-color: #adcdd2;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            border: none;
            border-radius: 30px; /* Modern pill shape matching inputs */
            padding: 0.8rem 4.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(173, 205, 210, 0.4);
            letter-spacing: 0.03em;
        }
        .login-button:hover {
            background-color: #92bdc3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(173, 205, 210, 0.6);
        }
        .login-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(173, 205, 210, 0.2);
        }
        .signup-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: #64748b;
            text-align: center;
            font-weight: 500;
        }
        .signup-link {
            color: #7ac5ca; /* Sign up link color matching mockup */
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .signup-link:hover {
            color: #5ab3bc;
            text-decoration: underline;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.4rem;
            font-weight: 600;
            padding-left: 0.5rem;
        }
        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            width: 100%;
        }

        /* Responsive styling */
        @media (max-width: 991px) {
            .left-panel {
                display: none;
            }
            .right-panel {
                width: 100%;
                height: auto;
                min-height: 100vh;
                padding: 4rem 2rem;
                overflow-y: visible;
            }
            .login-container {
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }
        }

        @media (max-width: 576px) {
            .right-panel {
                padding: 2.5rem 1.25rem;
            }
            .welcome-title {
                font-size: 2.2rem;
            }
            .welcome-subtitle {
                margin-bottom: 2rem;
            }
            .input-field {
                padding: 0.95rem 1.25rem;
                font-size: 0.95rem;
            }
            .login-button {
                padding: 0.8rem 4rem;
                font-size: 1.1rem;
            }
        }

        /* Adjustments for shorter browser heights on desktop/laptops */
        @media (min-width: 992px) and (max-height: 850px) {
            .left-panel {
                padding: 2.5rem 2rem;
            }
            .solustek-logo {
                max-width: 250px;
            }
            .illustration-container {
                margin-bottom: 2rem;
            }
            .working-illustration {
                width: 125%;
                transform: scale(1.25);
            }
            .right-panel {
                padding: 2rem;
            }
            .sp-logo-container {
                margin-bottom: 0.8rem;
            }
            .sp-logo {
                height: 75px;
            }
            .welcome-title {
                font-size: clamp(1.8rem, 2.8vw, 2.2rem);
            }
            .welcome-subtitle {
                margin-bottom: 1.2rem;
            }
            .input-group {
                margin-bottom: 0.9rem;
            }
            .input-field {
                padding: 0.85rem 1.15rem;
                font-size: 0.9rem;
            }
            .button-container {
                margin-top: 1.4rem;
                margin-bottom: 1.1rem;
            }
        }

        @media (min-width: 992px) and (max-height: 680px) {
            .left-panel {
                padding: 1.8rem 1.8rem;
            }
            .solustek-logo {
                max-width: 210px;
            }
            .illustration-container {
                margin-bottom: 1.5rem;
            }
            .working-illustration {
                width: 115%;
                transform: scale(1.15);
            }
            .right-panel {
                padding: 1.5rem;
            }
            .sp-logo-container {
                margin-bottom: 0.6rem;
            }
            .sp-logo {
                height: 60px;
            }
            .welcome-title {
                font-size: clamp(1.5rem, 2.5vw, 1.8rem);
            }
            .welcome-subtitle {
                margin-bottom: 1rem;
                font-size: 0.85rem;
            }
            .input-group {
                margin-bottom: 0.75rem;
            }
            .input-field {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
            }
            .button-container {
                margin-top: 1.1rem;
                margin-bottom: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Panel with Mint Teal Background, Solustek Logo, and Illustration -->
        <div class="left-panel">
            <div class="solustek-logo-container">
                <img src="{{ asset('images/solustek-logo.png') }}" alt="SOLUSTEK PT. PCR SOLUSI TEKNOLOGI" class="solustek-logo">
            </div>
            <div class="illustration-container">
                <img src="{{ asset('images/working-illustration.png') }}" alt="SiProjek Illustration" class="working-illustration">
            </div>
            <div></div> <!-- Bottom Spacer -->
        </div>

        <!-- Right Panel with Register Form -->
        <div class="right-panel">
            <div class="form-wrapper">
                <!-- SP Logo -->
                <div class="sp-logo-container">
                    <img src="{{ asset('images/sp-logo.png') }}" alt="SP Logo" class="sp-logo">
                </div>

                <!-- Titles -->
                <h1 class="welcome-title">Daftar Akun<br>SiProjek!</h1>
                <p class="welcome-subtitle">Solusi Pengelolaan Proyek PT PCR Solusi Teknologi</p>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="login-form">
                    @csrf

                    <!-- Name Field -->
                    <div class="input-group">
                        <label for="name" class="input-label">Nama Lengkap</label>
                        <input id="name" type="text" name="name" class="input-field" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required autofocus autocomplete="name">
                        @if($errors->has('name'))
                            <div class="error-message">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <!-- Email Field -->
                    <div class="input-group">
                        <label for="email" class="input-label">Email</label>
                        <input id="email" type="email" name="email" class="input-field" value="{{ old('email') }}" placeholder="Masukkan alamat email Anda" required autocomplete="username">
                        @if($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <!-- Password Field -->
                    <div class="input-group">
                        <label for="password" class="input-label">Password</label>
                        <input id="password" type="password" name="password" class="input-field" placeholder="Masukkan password baru Anda" required autocomplete="new-password">
                        @if($errors->has('password'))
                            <div class="error-message">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="input-group">
                        <label for="password_confirmation" class="input-label">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="input-field" placeholder="Masukkan kembali password Anda" required autocomplete="new-password">
                        @if($errors->has('password_confirmation'))
                            <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    <!-- Register Button -->
                    <div class="button-container">
                        <button type="submit" class="login-button">Daftar</button>
                    </div>

                    <!-- Sign In / Login Link -->
                    <p class="signup-text">
                        Sudah punya akun? <a href="{{ route('login') }}" class="signup-link">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>