@extends('layouts.app')

@section('title', 'Dashboard Admin - MoodTracker')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Admin</h1>
            <p class="text-gray-600">Panel de control para administradores de MoodTracker</p>
        </div>

        <!-- Error Alert -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtros de Fecha</h2>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="from" class="block text-sm font-medium text-gray-700 mb-2">Desde:</label>
                    <input type="date" name="from" id="from" value="{{ $filters['from'] }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1">
                    <label for="to" class="block text-sm font-medium text-gray-700 mb-2">Hasta:</label>
                    <input type="date" name="to" id="to" value="{{ $filters['to'] }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    Filtrar
                </button>
            </form>
        </div>

        <!-- KPIs Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Entries -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Entradas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $basicKpis->total_entries ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $basicKpis->total_users ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- IGB Score -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">IGB Promedio</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $igb ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- CQP Score -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">CQP Promedio</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $cqp ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- IGB Trend Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Tendencia IGB</h3>
                <canvas id="igbLine" width="400" height="200"></canvas>
            </div>

            <!-- Emotion KPIs -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">KPIs de Emociones</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">IEO (Energía)</span>
                        <span class="text-lg font-semibold text-blue-600">{{ $emotionKpis->ieo ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">IFC (Flujo)</span>
                        <span class="text-lg font-semibold text-green-600">{{ $emotionKpis->ifc ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">IAS (Soporte)</span>
                        <span class="text-lg font-semibold text-purple-600">{{ $emotionKpis->ias ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">IPF (Futuro)</span>
                        <span class="text-lg font-semibold text-orange-600">{{ $emotionKpis->ipf ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Alertas de Usuarios</h3>
            @if (isset($alerts) && $alerts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Entradas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Energía Media</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($alerts as $alert)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $alert->user_name ?? 'Usuario #' . $alert->user_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $alert->entries ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($alert->avg_energy ?? 0, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2">No hay alertas en el rango seleccionado</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validar que los datos existen antes de crear el gráfico
            const trendData = @json($trend ?? []);

            if (trendData && trendData.length > 0) {
                // Línea IGB
                const trendCtx = document.getElementById('igbLine').getContext('2d');
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendData.map(item => item.day),
                        datasets: [{
                            label: 'IGB',
                            data: trendData.map(item => item.igb),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.1,
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            } else {
                // Mostrar mensaje si no hay datos
                const canvas = document.getElementById('igbLine');
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#6B7280';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', canvas.width / 2, canvas.height / 2);
            }
        });
    </script>
@endsection
