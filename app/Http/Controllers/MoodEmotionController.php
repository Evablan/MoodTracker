<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoodEntryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class MoodEmotionController extends Controller
{
    public function create()
    {
        // Mantén aquí tu lógica de idioma / datos para la vista si la tenías
        return view('moods.create');
    }

    public function store(StoreMoodEntryRequest $request)
    {
        // 1) Usuario temporal hasta tener SSO
        $defaultEmail = config('moodtracker.default_user_email', 'eva@democorp.test');
        $user = DB::table('users')->where('email', $defaultEmail)->first();

        if (! $user) {
            return back()
                ->withErrors(['user' => 'No existe el usuario por defecto. Revisa config/moodtracker.php o el seed.'])
                ->withInput();
        }

        // 2) Mapear keys -> IDs reales (catálogos globales mientras no haya per-company)
        $emotion = DB::table('emotions')
            ->whereNull('company_id')
            ->where('key', $request->emotion_key)
            ->where('is_active', true)
            ->first();

        $cause = DB::table('causes')
            ->whereNull('company_id')
            ->where('key', $request->cause_key)
            ->where('is_active', true)
            ->first();

        if (! $emotion || ! $cause) {
            return back()->withErrors(['keys' => 'Emotion o cause no válidos.'])->withInput();
        }

        // 3) Cargar preguntas por key (solo las enviadas)
        $questionKeys = collect($request->input('answers', []))
            ->pluck('question_key')->filter()->values()->all();

        $questions = DB::table('questions')
            ->whereNull('company_id')
            ->whereIn('key', $questionKeys ?: ['__none__'])
            ->get()
            ->keyBy('key'); // lookup por key

        // 4) Guardar entry + answers en una transacción
        $now = Carbon::now();
        $entryId = null;

        DB::transaction(function () use ($request, $user, $emotion, $cause, $questions, $now, &$entryId) {
            // a) mood_entries
            $entryId = DB::table('mood_entries')->insertGetId([
                'company_id'    => $user->company_id,
                'department_id' => $user->department_id, // denormalizado para agregados
                'user_id'       => $user->id,
                'emotion_id'    => $emotion->id,
                'cause_id'      => $cause->id,
                'work_quality'  => (int) $request->work_quality,
                'entry_at'      => $now,
                'entry_date'    => $now->toDateString(),
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            // b) mood_entry_answers
            $answersPayload = [];

            foreach ($request->input('answers', []) as $a) {
                $q = $questions->get($a['question_key'] ?? null);
                if (! $q) continue; // ignora desconocidas

                $row = [
                    'mood_entry_id'     => $entryId,
                    'question_id'       => $q->id,
                    'answer_numeric'    => null,
                    'answer_bool'       => null,
                    'answer_option_key' => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

                switch ($q->type) {
                    case 'scale':
                        $val = (int) ($a['answer_numeric'] ?? 0);
                        $min = $q->min_value ?? 1;
                        $max = $q->max_value ?? 5;
                        $row['answer_numeric'] = max($min, min($max, $val));
                        break;

                    case 'bool':
                        $row['answer_bool'] = filter_var($a['answer_bool'] ?? false, FILTER_VALIDATE_BOOLEAN);
                        break;

                    case 'select':
                        $row['answer_option_key'] = Arr::get($a, 'answer_option_key');
                        break;
                }

                $answersPayload[] = $row;
            }

            if ($answersPayload) {
                DB::table('mood_entry_answers')->insert($answersPayload);
            }
        });

        return redirect()
            ->route('moods.create')
            ->with('success', __('moods.validated_success') . ' (ID ' . $entryId . ')');
    }
}
