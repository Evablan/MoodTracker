<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'consent_at')) {
                $table->timestampTz('consent_at')->nullable()->after('email_verified_at');
            }

            // Añadir department_id solo si no existe ya
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->foreignId('department_id')->nullable()->index()->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'consent_at')) {
                $table->dropColumn('consent_at');
            }

            if (Schema::hasColumn('users', 'department_id')) {
                // Si la columna proviene de esta migración, eliminar FK y columna con seguridad
                try {
                    $table->dropConstrainedForeignId('department_id');
                } catch (\Throwable $e) {
                    // Fallback si el nombre de la FK varía
                    if (method_exists($table, 'dropForeign')) {
                        try {
                            $table->dropForeign(['department_id']);
                        } catch (\Throwable $_) {
                        }
                    }
                    try {
                        $table->dropColumn('department_id');
                    } catch (\Throwable $_) {
                    }
                }
            }
        });
    }
};
