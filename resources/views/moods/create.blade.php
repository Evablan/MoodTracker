@extends('layouts.app')

@section('title', 'Formulario de Mood Emotion')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-8 py-12"> {{-- Contenedor principal --}}
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ __('moods.title') }}</h1>
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Errores de validación -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border border-red-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action= "{{ route('moods.store') }}" method="POST">
                    @csrf

                    {{-- Valores que espera el backend --}}
                    <input type="hidden" name="emotion_key" id="emotion_key" value="{{ old('emotion_key') }}">
                    <input type="hidden" name="cause_key" id="cause_key" value="{{ old('cause_key') }}">





                    {{-- Saludo personalizado según la hora --}}
                    <div class="text-center mb-4 md:mb-6">
                        <p class="text-base md:text-xl lg:text-lg text-gray-600 flex items-center justify-center gap-2"
                            id="greeting">
                            @php
                                $hour = now()->hour;
                                if ($hour >= 5 && $hour < 12) {
                                    echo '<span class="text-2xl">🌅</span>' . __('moods.greeting.morning');
                                } elseif ($hour >= 12 && $hour < 17) {
                                    echo '<span class="text-2xl">☀️</span>' . __('moods.greeting.afternoon');
                                } elseif ($hour >= 17 && $hour < 21) {
                                    echo '<span class="text-2xl">🌆</span>' . __('moods.greeting.evening');
                                } else {
                                    echo '<span class="text-2xl">🌙</span>' . __('moods.greeting.night');
                                }
                            @endphp
                        </p>
                    </div>


                    {{-- Indicador de progreso --}}
                    <div class="flex justify-center mb-6 md:mb-8">
                        <div class="flex items-center space-x-2 md:space-x-6 lg:space-x-4">
                            <div class="flex items-center">
                                <div class="w-6 h-6 md:w-10 md:h-10 lg:w-8 lg:h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-semibold transition-all duration-300 text-xs md:text-lg lg:text-sm"
                                    id="step-1">1</div>
                                <span
                                    class="ml-1 md:ml-3 lg:ml-2 text-xs md:text-lg lg:text-sm font-medium text-gray-600">{{ __('moods.steps.emotion') }}</span>
                            </div>
                            <div class="w-8 md:w-16 lg:w-12 h-0.5 bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 md:w-10 md:h-10 lg:w-8 lg:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-semibold transition-all duration-300 text-xs md:text-lg lg:text-sm"
                                    id="step-2">2</div>
                                <span
                                    class="ml-1 md:ml-3 lg:ml-2 text-xs md:text-lg lg:text-sm font-medium text-gray-400">{{ __('moods.steps.questions') }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-6 text-center">{{ __('moods.question') }}</p>

                    {{-- NUEVO: Calidad del trabajo (1–10) --}}
                    <div
                        class="bg-white shadow-lg rounded-xl p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 mt-6">
                        <label for="work_quality" class="block font-medium mb-3 md:mb-4 text-gray-800 text-sm md:text-base">
                            1) {{ __('moods.quality_question') }}
                        </label>
                        <div class="grid grid-cols-5 gap-2 md:gap-3">
                            @for ($i = 1; $i <= 10; $i++)
                                <label
                                    class="flex items-center justify-center rounded-lg cursor-pointer select-none outline-none"
                                    tabindex="0">
                                    <input type="radio" name="work_quality" value="{{ $i }}"
                                        {{ $i === 1 ? 'required' : '' }} class="hidden peer"
                                        {{ old('work_quality') == $i ? 'checked' : '' }}>
                                    {{-- Mantener en check las opciones --}}

                                    <div
                                        class="w-12 md:w-14 lg:w-16 text-center py-2 md:py-3 rounded-lg border bg-white hover:bg-gray-50 transition-colors duration-200 peer-checked:bg-blue-100 peer-checked:border-blue-500">
                                        <span
                                            class="font-semibold text-gray-800 peer-checked:text-primary">{{ $i }}</span>

                                    </div>

                                </label>
                            @endfor
                        </div>
                        {{-- Errores de validación --}}
                        @error('work_quality')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">{{ __('moods.quality_hint') }}</p>
                    </div>

                    {{-- Pregunta 2: Selección de emociones --}}
                    <h2 class="text-center font-medium mb-6 text-gray-800 text-lg mt-8">
                        2) {{ __('moods.emotion_today') }}
                    </h2>

                    <div class="grid grid-cols-5 gap-4 md:gap-6 lg:gap-8 mb-8">
                        @foreach (__('moods.emotions') as $value => $label)
                            @php list($emoji, $text) = explode('|', $label); @endphp

                            <label class="cursor-pointer flex flex-col items-center group">
                                <input type="radio" name="emotion" value="{{ $value }}" class="hidden peer"
                                    data-label="{{ $text }}" {{ $loop->first ? 'required' : '' }}
                                    {{ old('emotion') == $value ? 'checked' : '' }}>

                                <div
                                    class="bg-transparent rounded-full p-4 md:p-8 lg:p-6 hover:bg-blue-50 transition-all duration-300 transform hover:scale-110 border-2 border-transparent hover:border-blue-300 peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:shadow-lg">
                                    <span
                                        class="text-6xl md:text-8xl lg:text-9xl leading-none filter group-hover:drop-shadow-lg transition-all duration-300"
                                        role="img" aria-label="{{ $text }}">{{ $emoji }}</span>
                                </div>
                                <span
                                    class="mt-2 md:mt-4 lg:mt-3 text-sm md:text-xl lg:text-lg font-semibold text-gray-800 group-hover:text-blue-600 peer-checked:text-blue-600 transition-colors duration-300 text-center">{{ $text }}</span>
                            </label>
                        @endforeach
                    </div> {{-- Cierre del contenedor de emociones --}}
                    {{-- Errores de validación --}}
                    @error('emotion')
                        <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                    @enderror

                    {{-- Bloque de preguntas dinámicas por emoción --}}
                    <div id="questions" class="hidden space-y-6">
                        <p class="text-lg font-semibold">{{ __('moods.repondre_questions') }}</p>

                        <div
                            class="bg-white shadow-lg rounded-xl p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                            <p id="q1" class="font-medium mb-3 md:mb-4 text-gray-800 text-sm md:text-base">1. Lorem
                                ipsum
                                dolor
                                sit amet?</p>
                            <div class="grid grid-cols-5 gap-2 md:gap-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label
                                        class="flex items-center justify-center rounded-lg cursor-pointer select-none outline-none"
                                        tabindex="0">
                                        <input type="radio" name="answer_1" value="{{ $i }}"
                                            class="hidden peer" {{ old('answer_1') == $i ? 'checked' : '' }}>
                                        <div
                                            class="w-12 md:w-14 lg:w-16 text-center py-2 md:py-3 rounded-lg border bg-white hover:bg-gray-50 transition-colors duration-200 peer-checked:bg-blue-100 peer-checked:border-blue-500">
                                            <span
                                                class="font-semibold text-gray-800 peer-checked:text-blue-600">{{ $i }}</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            {{-- Errores de validación --}}
                            @error('answer_1')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2" id="scale-q1">
                                {{ __('moods.scale_explanations.q1') }}</p>
                        </div>

                        <div
                            class="bg-white shadow-lg rounded-xl p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                            <p id="q2" class="font-medium mb-3 md:mb-4 text-gray-800 text-sm md:text-base">2.
                                Consectetur
                                adipiscing elit?</p>
                            <div class="grid grid-cols-5 gap-2 md:gap-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label
                                        class="flex items-center justify-center rounded-lg cursor-pointer select-none outline-none"
                                        tabindex="0">
                                        <input type="radio" name="answer_2" value="{{ $i }}"
                                            class="hidden peer" {{ old('answer_2') == $i ? 'checked' : '' }}>
                                        <div
                                            class="w-12 md:w-14 lg:w-16 text-center py-2 md:py-3 rounded-lg border bg-white hover:bg-gray-50 transition-colors duration-200 peer-checked:bg-blue-100 peer-checked:border-blue-500">
                                            <span
                                                class="font-semibold text-gray-800 peer-checked:text-blue-600">{{ $i }}</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            @error('answer_2')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">{{ __('moods.scale_explanations.q2') }}</p>
                        </div>

                        <div
                            class="bg-white shadow-lg rounded-xl p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                            <p id="q3" class="font-medium mb-3 md:mb-4 text-gray-800 text-sm md:text-base">3. Sed
                                do
                                eiusmod
                                tempor?</p>
                            <div class="grid grid-cols-5 gap-2 md:gap-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label
                                        class="flex items-center justify-center rounded-lg cursor-pointer select-none outline-none"
                                        tabindex="0">
                                        <input type="radio" name="answer_3" value="{{ $i }}"
                                            class="hidden peer" {{ old('answer_3') == $i ? 'checked' : '' }}>
                                        <div
                                            class="w-12 md:w-14 lg:w-16 text-center py-2 md:py-3 rounded-lg border bg-white hover:bg-gray-50 transition-colors duration-200 peer-checked:bg-blue-100 peer-checked:border-blue-500">
                                            <span
                                                class="font-semibold text-gray-800 peer-checked:text-blue-600">{{ $i }}</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            @error('answer_3')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">{{ __('moods.scale_explanations.q3') }}</p>
                        </div>

                        <div
                            class="bg-white shadow-lg rounded-xl p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                            <p id="q4" class="font-medium mb-3 md:mb-4 text-gray-800 text-sm md:text-base">4. Sed
                                do
                                eiusmod
                                tempor?</p>
                            <div class="grid grid-cols-5 gap-2 md:gap-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label
                                        class="flex items-center justify-center rounded-lg cursor-pointer select-none outline-none"
                                        tabindex="0">
                                        <input type="radio" name="answer_4" value="{{ $i }}"
                                            class="hidden peer" {{ old('answer_4') == $i ? 'checked' : '' }}>
                                        <div
                                            class="w-12 md:w-14 lg:w-16 text-center py-2 md:py-3 rounded-lg border bg-white hover:bg-gray-50 transition-colors duration-200 peer-checked:bg-blue-100 peer-checked:border-blue-500">
                                            <span
                                                class="font-semibold text-gray-800 peer-checked:text-blue-600">{{ $i }}</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            @error('answer_4')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">{{ __('moods.scale_explanations.q4') }}</p>
                        </div>
                    </div>

                    {{-- NUEVO: Causa de la emoción --}}
                    <div
                        class="bg-white shadow-lg rounded-xl p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 mb-8 mt-8">
                        <p class="font-medium mb-3 md:mb-4 text-gray-800 text-sm md:text-base">

                            3) {{ __('moods.cause_question') }} <span id="selectedEmotion"
                                class="ml-1 inline-block px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 font-semibold"></span>?
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <label
                                class="flex items-center p-2 md:p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 min-h-[44px]">
                                <input type="radio" name="cause" value="work" required
                                    class="form-radio text-primary h-4 w-4 md:h-5 md:w-5"
                                    {{ old('cause', 'work') == 'work' ? 'checked' : '' }}>
                                <span
                                    class="ml-2 md:ml-3 text-gray-700 font-medium text-sm md:text-base">{{ __('moods.cause.work') }}</span>
                            </label>
                            <label
                                class="flex items-center p-2 md:p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 min-h-[44px]">
                                <input type="radio" name="cause" value="personal"
                                    class="form-radio text-primary h-4 w-4 md:h-5 md:w-5"
                                    {{ old('cause') == 'personal' ? 'checked' : '' }}>
                                <span
                                    class="ml-2 md:ml-3 text-gray-700 font-medium text-sm md:text-base">{{ __('moods.cause.personal') }}</span>
                            </label>
                            <label
                                class="flex items-center p-2 md:p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 min-h-[44px]">
                                <input type="radio" name="cause" value="both"
                                    class="form-radio text-primary h-4 w-4 md:h-5 md:w-5"
                                    {{ old('cause') == 'both' ? 'checked' : '' }}>
                                <span
                                    class="ml-2 md:ml-3 text-gray-700 font-medium text-sm md:text-base">{{ __('moods.cause.both') }}</span>
                            </label>

                        </div>
                        @error('cause')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Botón de envío --}}
                    <p id="formHelper" class="text-center text-sm text-gray-500 mb-2 hidden">
                        {{ __('moods.complete_fields_hint') }}</p>
                    <button id="submitBtn" type="submit"
                        class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                        {{ __('moods.submit') }}
                    </button>

                </form>

                @push('scripts')
                    @vite('resources/js/mood_form.js')
                @endpush
            </div> {{-- Cierre del contenedor interno --}}
        </div> {{-- Cierre del contenedor con sombra --}}
    </div> {{-- Cierre del contenedor principal --}}

    <script>
        window.emotionQuestions = @json(__('moods.questions'));
        console.log('Preguntas cargadas:', window.emotionQuestions);
    </script>

@endsection
