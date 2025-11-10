<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absence>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance(fake()->dateTimeBetween('-3 months', 'now'))->startOfDay();
        $days = fake()->numberBetween(1, 7);
        $end = (clone $start)->addDays($days - 1);

        $userId = User::query()->inRandomOrder()->value('id');
        if (!$userId) {
            $userId = User::factory()->create()->id;
        }

        return [
            'user_id' => $userId,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'leave_type' => fake()->randomElement(['sick_leave', 'personal_leave', 'vacation']),
            'days' => $days,
        ];
    }
}
