<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MoodTracker — Iniciar sesión</title>
    <!-- Tailwind CDN (rápido para MVP) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="color-scheme" content="light dark">
</head>

<body class="min-h-dvh bg-gradient-to-br from-slate-900 via-indigo-900 to-fuchsia-800 text-slate-100">
    <div class="relative isolate">
        <!-- Glow decorativo -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
        </div>

        <main class="mx-auto flex min-h-dvh max-w-7xl items-center justify-center px-6">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="rounded-2xl bg-white/10 p-8 shadow-2xl ring-1 ring-white/20 backdrop-blur">
                    <!-- Marca -->
                    <div class="mb-6 flex items-center gap-3">
                        <svg class="h-8 w-8 text-fuchsia-300" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true">
                            <path
                                d="M12 3c4.97 0 9 3.582 9 8 0 5.25-5.25 6.75-9 10-3.75-3.25-9-4.75-9-10 0-4.418 4.03-8 9-8z" />
                        </svg>
                        <h1 class="text-xl font-semibold tracking-tight">MoodTracker</h1>
                    </div>

                    <h2 class="mb-2 text-2xl font-bold leading-tight">Inicia sesión</h2>
                    <p class="mb-8 text-sm text-slate-300">
                        Accede con tu cuenta corporativa. No compartimos tu contraseña; solo confirmamos tu identidad
                        con Google o Microsoft.
                    </p>

                    <div class="space-y-3">
                        <!-- Google -->
                        <a href="{{ route('google.redirect') }}"
                            class="group inline-flex w-full items-center justify-center gap-3 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-base font-medium text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-fuchsia-300">
                            <svg class="h-5 w-5" viewBox="0 0 533.5 544.3" aria-hidden="true">
                                <path fill="#EA4335"
                                    d="M533.5 278.4c0-18.6-1.7-37-5-54.8H272v103.8h147.3c-6.4 34.4-25.8 63.5-55 83v68h88.8c52.1-48 80.4-118.7 80.4-200z" />
                                <path fill="#34A853"
                                    d="M272 544.3c74.9 0 137.9-24.8 183.8-67.1l-88.8-68c-24.6 16.6-56.1 26.3-95 26.3-72.9 0-134.7-49.2-156.8-115.3H24.9v72.4C70.4 488.6 164.9 544.3 272 544.3z" />
                                <path fill="#4A90E2"
                                    d="M115.2 320.2c-10.2-30.4-10.2-63.2 0-93.6V154.2H24.9c-41.5 82.9-41.5 180.9 0 263.8l90.3-97.8z" />
                                <path fill="#FBBC05"
                                    d="M272 107.7c40 0 76 13.8 104.3 40.9l78.2-78.2C409.8 25.7 346.8 0 272 0 164.9 0 70.4 55.7 24.9 154.2l90.3 72.4C137.2 156.9 199.1 107.7 272 107.7z" />
                            </svg>
                            <span>Continuar con Google</span>
                        </a>

                        <!-- Microsoft -->
                        {{-- <a href="{{ route('azure.redirect') }}"
                            class="group inline-flex w-full items-center justify-center gap-3 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-base font-medium text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-fuchsia-300">
                            <svg class="h-5 w-5" viewBox="0 0 23 23" aria-hidden="true">
                                <rect x="1" y="1" width="9.5" height="9.5" fill="#f25022" />
                                <rect x="12.5" y="1" width="9.5" height="9.5" fill="#7fba00" />
                                <rect x="1" y="12.5" width="9.5" height="9.5" fill="#00a4ef" />
                                <rect x="12.5" y="12.5" width="9.5" height="9.5" fill="#ffb900" />
                            </svg>
                            <span>Continuar con Microsoft</span>
                        </a> --}}
                    </div>

                    <div class="mt-6 text-xs leading-5 text-slate-400">
                        Al continuar, confirmas que estás autorizado para usar tu cuenta de empresa. <br>
                        Tus respuestas se muestran de forma agregada y nunca publicamos datos de grupos pequeños.
                    </div>
                </div>

                <p class="mt-6 text-center text-xs text-slate-300/80">
                    © {{ date('Y') }} MoodTracker · Privacidad · Seguridad
                </p>
            </div>
        </main>
    </div>
</body>

</html>
