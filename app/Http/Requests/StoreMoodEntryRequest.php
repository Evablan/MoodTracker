<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMoodEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // luego lo ataremos a auth/roles
    }

    public function rules(): array
    {
        return [
            'work_quality'        => ['required', 'integer', 'between:1,10'],
            'emotion_key'         => ['required', 'string', 'max:50'],
            'cause_key'           => ['required', 'string', 'max:50'],

            // answers es una lista: [{question_key, answer_numeric|answer_bool|answer_option_key}]
            'answers'             => ['array'],
            'answers.*.question_key'      => ['required', 'string', 'max:100'],
            'answers.*.answer_numeric'    => ['nullable', 'integer'],   // 1..5 si es escala (lo validamos luego)
            'answers.*.answer_bool'       => ['nullable', 'boolean'],
            'answers.*.answer_option_key' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'work_quality.between' => 'La calidad debe estar entre 1 y 10.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'emotion_key' => $this->input('emotion_key')
                ?? $this->input('emotion')
                ?? $this->input('emotionKey'),

            'cause_key' => $this->input('cause_key')
                ?? $this->input('cause'),
        ]);
    }
}
