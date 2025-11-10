<!DOCTYPE html>
<html lang="es" data-theme="yellow">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MoodTracker — Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <meta name="color-scheme" content="light dark">
</head>

<body>
    <!-- Hero Background: Gradiente emocional con profundidad -->
    <div class="hero-background">
        <div class="hero-gradient"></div>
        <!-- Elementos de profundidad adicionales -->
        <div class="depth-layer depth-layer-1"></div>
        <div class="depth-layer depth-layer-2"></div>
        <div class="depth-layer depth-layer-3"></div>
    </div>

    <!-- Main Content -->
    <main class="login-main" role="main">
        <!-- Logo Section (Left) -->
        <div class="logo-section">
            <img src="{{ asset('brand/moodtracker-logo.png') }}" alt="MoodTracker Logo" class="brand-logo">
        </div>

        <!-- Login Card (Right) -->
        <section class="glass-card" role="form" aria-labelledby="login-title">
            <!-- Títulos -->
            <h1 id="login-title" class="login-title">WELCOME MOOD TRACKER</h1>
            <h2 class="login-subtitle">LOG IN TO CONTINUE</h2>

            <!-- Formulario tradicional (Email + Password) -->
            <form id="login-form" method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <span class="sr-only">Email</span>
                    </label>
                    <div class="input-wrapper">
                        <svg class="input-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="biswasajay904@email.com" value="{{ old('email') }}" required
                            autocomplete="email"
                            aria-describedby="@if ($errors->has('email')) email-error @endif">
                    </div>
                    @error('email')
                        <span id="email-error" class="error-message" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <span class="sr-only">Contraseña</span>
                    </label>
                    <div class="input-wrapper">
                        <svg class="input-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                            required autocomplete="current-password"
                            aria-describedby="@if ($errors->has('password')) password-error @endif password-toggle-desc">
                        <button type="button" id="password-toggle" class="password-toggle"
                            aria-label="Mostrar contraseña" aria-pressed="false"
                            aria-describedby="password-toggle-desc">
                            SHOW
                        </button>
                        <span id="password-toggle-desc" class="sr-only">Toggle para mostrar u ocultar la
                            contraseña</span>
                    </div>
                    @error('password')
                        <span id="password-error" class="error-message" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- CTA Button -->
                <button type="submit" id="submit-button" class="cta-button" aria-label="Iniciar sesión">
                    <span>Proceed to my Account</span>
                    <svg class="arrow-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </button>
            </form>

            <!-- Separador -->
            <div class="separator">
                <div class="separator-line"></div>
                <span class="separator-text">or</span>
                <div class="separator-line"></div>
            </div>

            <!-- Google Login Button -->
            <div class="google-login-wrapper">
                <a href="{{ route('google.redirect') }}" id="google-login-button" class="google-button"
                    aria-label="Iniciar sesión con Google">
                    <svg class="google-icon" viewBox="0 0 533.5 544.3" aria-hidden="true">
                        <path fill="#EA4335"
                            d="M533.5 278.4c0-18.6-1.7-37-5-54.8H272v103.8h147.3c-6.4 34.4-25.8 63.5-55 83v68h88.8c52.1-48 80.4-118.7 80.4-200z" />
                        <path fill="#34A853"
                            d="M272 544.3c74.9 0 137.9-24.8 183.8-67.1l-88.8-68c-24.6 16.6-56.1 26.3-95 26.3-72.9 0-134.7-49.2-156.8-115.3H24.9v72.4C70.4 488.6 164.9 544.3 272 544.3z" />
                        <path fill="#4A90E2"
                            d="M115.2 320.2c-10.2-30.4-10.2-63.2 0-93.6V154.2H24.9c-41.5 82.9-41.5 180.9 0 263.8l90.3-97.8z" />
                        <path fill="#FBBC05"
                            d="M272 107.7c40 0 76 13.8 104.3 40.9l78.2-78.2C409.8 25.7 346.8 0 272 0 164.9 0 70.4 55.7 24.9 154.2l90.3 72.4C137.2 156.9 199.1 107.7 272 107.7z" />
                    </svg>
                    <span>Continue with Google</span>
                    <svg class="arrow-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
            </div>

            <!-- Info Text -->
            <p class="login-info">
                Secure login with your corporate Google account
            </p>
        </section>
    </main>

    <!-- JavaScript -->
    <script src="{{ asset('js/login.js') }}" defer></script>
</body>

</html>
