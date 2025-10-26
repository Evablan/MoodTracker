<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ResetPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'evablancomart@gmail.com')->first();

        if ($user) {
            $user->password = bcrypt('secret123');
            $user->save();
            $this->command->info("✅ Contraseña actualizada para: " . $user->name);
        } else {
            $this->command->error("❌ Usuario no encontrado");
        }
    }
}
