<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'MoodTracker')</title>
    <x-brand-head-icons />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">

    {{-- Selector de idiomas --}}
    <div class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('brand/moodtracker-logo.png') }}" alt="MoodTracker" class="h-8 w-8"
                        style="height:96px; width:auto;">

                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('lang', 'es') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ app()->getLocale() == 'es' ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        🇪🇸 ES
                    </a>
                    <a href="{{ route('lang', 'en') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ app()->getLocale() == 'en' ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        🇺🇸 EN
                    </a>
                    <a href="{{ route('lang', 'fr') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ app()->getLocale() == 'fr' ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        🇫🇷 FR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">

        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>
