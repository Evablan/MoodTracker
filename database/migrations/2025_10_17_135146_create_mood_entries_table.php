<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();

            // Multi-tenant + agregados rápidos
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete(); // denormalizado
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('emotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cause_id')->constrained()->cascadeOnDelete();

            $table->unsignedSmallInteger('work_quality'); // 1..10
            $table->timestampTz('entry_at');  // fecha-hora con zona
            $table->date('entry_date');       // fecha plana
            $table->timestampsTz();
            $table->softDeletesTz();

            // Índices de panel
            $table->index(['company_id', 'department_id', 'entry_date']);
            $table->index(['company_id', 'emotion_id', 'entry_date']);
            $table->index(['company_id', 'cause_id', 'entry_date']);
        });

        DB::statement("ALTER TABLE mood_entries ADD CONSTRAINT chk_work_quality CHECK (work_quality BETWEEN 1 AND 10)");
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_entries');
    }
};
