<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Resetear contraseña del usuario admin
$user = User::where('email', 'evablancomart@gmail.com')->first();

if ($user) {
    $user->password = bcrypt('secret123');
    $user->save();
    echo "✅ Contraseña actualizada para: " . $user->name . "\n";
    echo "📧 Email: " . $user->email . "\n";
    echo "🔑 Nueva contraseña: secret123\n";
} else {
    echo "❌ Usuario no encontrado\n";
}
