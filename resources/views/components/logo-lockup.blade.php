<div class="flex items-center gap-4">
    <!-- Logo exacto replicando la segunda imagen -->
    <svg width="48" height="48" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Gradiente más sutil como en la referencia -->
            <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#4A90E2" />
                <stop offset="30%" stop-color="#7B68EE" />
                <stop offset="70%" stop-color="#DA70D6" />
                <stop offset="100%" stop-color="#FF8A65" />
            </linearGradient>
        </defs>

        <!-- Anillo exterior con gradiente -->
        <circle cx="50" cy="50" r="45" fill="url(#logoGradient)" stroke="none" />

        <!-- Círculo interior blanco -->
        <circle cx="50" cy="50" r="35" fill="white" />

        <!-- Símbolo de onda analytics (forma orgánica como en la referencia) -->
        <g fill="#0F2742" stroke="#0F2742">
            <!-- Onda principal característica -->
            <path d="M25 45 Q35 35 45 45 T65 40 Q70 42 75 45" stroke-width="4" fill="none" stroke-linecap="round" />

            <!-- Puntos de datos -->
            <circle cx="25" cy="45" r="3" />
            <circle cx="35" cy="40" r="3" />
            <circle cx="45" cy="45" r="3" />
            <circle cx="55" cy="42" r="3" />
            <circle cx="65" cy="40" r="3" />
            <circle cx="75" cy="45" r="3" />

            <!-- Barras verticales sutiles -->
            <rect x="33" y="50" width="2" height="15" rx="1" />
            <rect x="43" y="52" width="2" height="13" rx="1" />
            <rect x="53" y="48" width="2" height="17" rx="1" />
            <rect x="63" y="50" width="2" height="15" rx="1" />
        </g>
    </svg>

    <!-- Texto exacto como en la referencia -->
    <div class="flex flex-col leading-none">
        <span class="text-[#0F2742] font-black text-2xl tracking-tight">Mood</span>
        <span class="text-[#0F2742] font-black text-2xl tracking-tight">Tracker</span>
    </div>
</div>
