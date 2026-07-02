<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password - SiProjek</title>

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
            padding: 3.5rem;
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
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }
        .sp-logo {
            height: clamp(90px, 12vh, 120px);
            width: auto;
            object-fit: contain;
        }
        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 4.5vw, 3.8rem);
            font-weight: 800;
            color: #000000;
            text-align: center;
            line-height: 1.15;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .welcome-subtitle {
            font-family: 'Playfair Display', serif;
            font-size: clamp(0.95rem, 1.3vw, 1.05rem);
            font-weight: 700;
            color: #000000;
            text-align: center;
            text-decoration: underline;
            margin-bottom: clamp(2rem, 4vh, 3.5rem);
            text-underline-offset: 4px;
            line-height: 1.4;
        }
        .login-form {
            width: 100%;
            max-width: 460px;
        }
        .input-group {
            margin-bottom: 1.6rem;
            display: flex;
            flex-direction: column;
        }
        .input-label {
            font-size: 1.05rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 0.6rem;
        }
        .input-field {
            background-color: #e2e8f0; /* Soft light gray input background */
            border: none;
            outline: none;
            border-radius: 28px; /* Rounded pill shape inputs matching mockup */
            padding: 1.15rem 1.5rem;
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
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
            margin-top: 2.5rem;
            margin-bottom: 2rem;
        }
        .login-button {
            background-color: #adcdd2;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            border: none;
            border-radius: 30px; /* Modern pill shape matching inputs */
            padding: 0.95rem 5rem;
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
        .forgot-link {
            color: #7ac5ca;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }
        .forgot-link:hover {
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
                font-size: 2.4rem;
            }
            .welcome-subtitle {
                margin-bottom: 2.2rem;
            }
            .input-field {
                padding: 1rem 1.3rem;
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
                padding: 2.5rem;
            }
            .sp-logo-container {
                margin-bottom: 1.5rem;
            }
            .sp-logo {
                height: 95px;
            }
            .welcome-title {
                font-size: clamp(2.4rem, 3.5vw, 3.2rem);
            }
            .welcome-subtitle {
                margin-bottom: 2.2rem;
            }
            .input-group {
                margin-bottom: 1.2rem;
            }
            .input-field {
                padding: 1.05rem 1.4rem;
                font-size: 1rem;
            }
            .button-container {
                margin-top: 2rem;
                margin-bottom: 1.5rem;
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
                padding: 2rem;
            }
            .sp-logo-container {
                margin-bottom: 1rem;
            }
            .sp-logo {
                height: 80px;
            }
            .welcome-title {
                font-size: clamp(2rem, 3vw, 2.6rem);
            }
            .welcome-subtitle {
                margin-bottom: 1.6rem;
                font-size: 0.9rem;
            }
            .input-group {
                margin-bottom: 1rem;
            }
            .input-field {
                padding: 0.95rem 1.25rem;
                font-size: 0.95rem;
            }
            .button-container {
                margin-top: 1.6rem;
                margin-bottom: 1.2rem;
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

        <!-- Right Panel with Forgot Password Form -->
        <div class="right-panel">
            <div class="form-wrapper">
                <!-- SP Logo -->
                <div class="sp-logo-container">
                    <img src="{{ asset('images/sp-logo.png') }}" alt="SP Logo" class="sp-logo">
                </div>

                <!-- Titles -->
                <h1 class="welcome-title">Lupa Password?</h1>
                
                <!-- Session status / Alert success -->
                @if (session('status'))
                    <div class="alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}" class="login-form">
                    @csrf

                    <!-- Email Address Field -->
                    <div class="input-group">
                        <label for="email" class="input-label">Email</label>
                        <input id="email" type="email" name="email" class="input-field" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus>
                        @if($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="button-container">
                        <button type="submit" class="login-button">Kirim Tautan Reset</button>
                    </div>

                    <!-- Back to Login Link -->
                    <p class="signup-text">
                        Ingat password Anda? <a href="{{ route('login') }}" class="signup-link">Login kembali</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>