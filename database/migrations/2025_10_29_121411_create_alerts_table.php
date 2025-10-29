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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('rule', 50); // "low_igb", "low_energy", etc.
            $table->integer('threshold'); // 60, 5, etc.
            $table->string('period_week', 10); // "2025-W44"
            $table->string('status', 10)->default('open'); // 'open' o 'closed'
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampsTz();

            // Índices para consultas rápidas
            $table->index(['department_id', 'period_week', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
