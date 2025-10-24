<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            // Si manejas multiempresa, referencia a tabla companies; si no, déjalo nullable:
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();

            // Evita duplicados del mismo rol por usuario/empresa:
            $table->primary(['user_id', 'role_id', 'company_id']);
            // Índices de consulta:
            $table->index(['user_id', 'company_id']);
            $table->index(['role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
