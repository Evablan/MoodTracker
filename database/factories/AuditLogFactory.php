<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'actor_id' => User::factory(),
            'action' => fake()->randomElement(['alert.created', 'alert.closed', 'alert.updated']),
            'entity_type' => 'Alert',
            'entity_id' => Alert::factory(),
            'meta' => [
                'note' => fake()->sentence(3),
                'ip' => fake()->ipv4(),
            ],
            'created_at' => now()->subDays(fake()->numberBetween(0, 21)),
        ];
    }
}
