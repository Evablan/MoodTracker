<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete(); // ID del usuario que realizó la acción, null si es un sistema automático
            $table->string('action', 100); // "create", "update", "delete", "login", "logout", "other"
            $table->string('entity_type', 50); //Alerts, setting, user
            $table->unsignedBigInteger('entity_id')->nullable(); // ID del registro afectado
            $table->jsonb('meta')->nullable(); // Datos adicionales en JSON
            $table->timestampTz('created_at'); // Solo created_at, sin updated_at


            // Índices para consultas rápidas
            $table->index(['actor_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
