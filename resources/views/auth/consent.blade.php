<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Consentimiento — MoodTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-dvh bg-slate-50 flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-2xl font-bold mb-3">Consentimiento informado</h1>
        <p class="text-slate-700 mb-4">
            Este servicio recoge tus emociones y textos para mostrar <strong>tendencias agregadas</strong>
            a tu empresa. <strong>No</strong> se mostrarán datos de grupos pequeños (regla n≥5).
            Tus datos viajan cifrados y puedes pedir su eliminación según la política de tu empresa.
        </p>

        <h2 class="font-semibold mt-4 mb-2">En resumen:</h2>
        <ul class="list-disc pl-6 text-slate-700 space-y-1">
            <li>Guardamos tu consentimiento con fecha y hora.</li>
            <li>Solo personal autorizado puede ver métricas agregadas.</li>
            <li>No se muestran valores individuales ni grupos &lt; 5 personas.</li>
        </ul>

        <form method="POST" action="{{ route('consent.store') }}" class="mt-6 flex gap-3">
            @csrf
            <input type="checkbox" name="accept_terms" value="1" checked hidden>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                Acepto y continuar
            </button>
            <a href="/logout" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
                No aceptar
            </a>
        </form>

        <p class="mt-4 text-xs text-slate-500">
            Responsable del tratamiento: Tu empresa. Finalidad: bienestar laboral.
            Conservación: según política de retención. Derechos: acceso, rectificación, supresión.
        </p>
    </div>
</body>

</html>
