<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => fake()->unique()->randomElement(['theme_color', 'max_users', 'backup_frequency', 'ui_language', 'notifications_enabled']),
            'value' => fake()->randomElement(['blue', 100, 'daily', 'es', true]),
        ];
    }
}
