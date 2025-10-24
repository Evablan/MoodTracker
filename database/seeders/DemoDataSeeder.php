<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seguridad: solo en local
        if (!app()->environment('local')) {
            $this->command?->warn('DemoDataSeeder: solo se ejecuta en local.');
            return;
        }

        // ---- Configurable por .env (opcional) ----
        $target = (int) env('DEMO_TOTAL_ENTRIES', 500); // nº de entradas a generar
        // ------------------------------------------

        // Asegúrate de que los catálogos existen
        $companyId = DB::table('companies')->where('slug', 'democorp')->value('id')
            ?? DB::table('companies')->value('id');

        if (!$companyId) {
            $this->command?->error('No hay companies. Ejecuta primero los seeders base: php artisan db:seed');
            return;
        }

        $departmentIds = DB::table('departments')->where('company_id', $companyId)->pluck('id')->all();
        $emotions      = DB::table('emotions')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key'])->all();
        $causes        = DB::table('causes')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key'])->all();
        $questions     = DB::table('questions')->whereNull('company_id')->orderBy('sort_order')->get(['id', 'key', 'type', 'min_value', 'max_value'])->all();

        if (!$departmentIds || !$emotions || !$causes || !$questions) {
            $this->command?->error('Faltan catálogos (departments/emotions/causes/questions). Ejecuta: php artisan db:seed');
            return;
        }

        // Limpia SOLO datos operativos para no duplicar
        DB::statement('TRUNCATE TABLE public.mood_entry_answers, public.mood_entries RESTART IDENTITY CASCADE;');

        $now     = now();
        $startTs = now()->subDays(27)->startOfDay()->timestamp; // últimas 4 semanas
        $endTs   = now()->endOfDay()->timestamp;

        for ($i = 0; $i < $target; $i++) {
            // Fecha/hora aleatoria dentro del rango
            $ts        = random_int($startTs, $endTs);
            $entryAt   = Carbon::createFromTimestamp($ts);
            $entryDate = $entryAt->toDateString();

            // Selecciones aleatorias
            $deptId = $departmentIds[array_rand($departmentIds)];
            $emo    = $emotions[array_rand($emotions)];
            $cause  = $causes[array_rand($causes)];

            // Calidad del trabajo (1..10)
            $workQuality = random_int(4, 9);

            // Inserta la entrada
            $entryId = DB::table('mood_entries')->insertGetId([
                'company_id'    => $companyId,
                'department_id' => $deptId,
                'user_id'       => null, // anónimo
                'emotion_id'    => $emo->id,
                'cause_id'      => $cause->id,
                'work_quality'  => $workQuality,
                'entry_at'      => $entryAt,
                'entry_date'    => $entryDate,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            // Respuestas según el tipo de pregunta
            foreach ($questions as $q) {
                $answerData = [
                    'mood_entry_id'     => $entryId,
                    'question_id'       => $q->id,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

                // Insertar respuesta según el tipo de pregunta
                if ($q->type === 'scale') {
                    $val = random_int($q->min_value ?? 1, $q->max_value ?? 5);
                    $answerData['answer_numeric'] = $val;
                    $answerData['answer_bool'] = null;
                    $answerData['answer_option_key'] = null;
                } elseif ($q->type === 'bool') {
                    $answerData['answer_numeric'] = null;
                    $answerData['answer_bool'] = (bool) random_int(0, 1);
                    $answerData['answer_option_key'] = null;
                } elseif ($q->type === 'select') {
                    // Para select, usar una opción aleatoria si hay opciones disponibles
                    $options = json_decode($q->options_json ?? '[]', true);
                    if (!empty($options)) {
                        $randomOption = $options[array_rand($options)];
                        $answerData['answer_numeric'] = null;
                        $answerData['answer_bool'] = null;
                        $answerData['answer_option_key'] = $randomOption['key'] ?? 'option_' . random_int(1, 3);
                    } else {
                        // Si no hay opciones, usar un valor por defecto
                        $answerData['answer_numeric'] = null;
                        $answerData['answer_bool'] = null;
                        $answerData['answer_option_key'] = 'default_option';
                    }
                }

                DB::table('mood_entry_answers')->insert($answerData);
            }
        }

        $this->command?->info("OK: generadas {$target} entradas demo con respuestas.");
    }
}
