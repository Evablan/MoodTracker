<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;



class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'rule' => fake()->randomElement(['low_igb', 'low_energy', 'low_flow', 'low_support']),
            'threshold' => setting('iec_alert_threshold', 60) + fake()->numberBetween(-15, 5),
            'period_week' => now()->subWeeks(fake()->numberBetween(0, 4))->format('Y-\WW'),
            'status' => fake()->randomElement(['open', 'closed']),
            'created_by' => User::factory(),
            'closed_at' => fake()->optional(0.4)->dateTimeBetween('-3 weeks', 'now'),
        ];
    }
}
