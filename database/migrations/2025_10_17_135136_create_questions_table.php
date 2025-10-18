<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('emotion_id')->nullable()->constrained()->nullOnDelete(); // específica de emoción o global
            $table->string('key');     // estable (ej: q_intensity)
            $table->string('prompt');  // texto visible (el front lo traduce)
            $table->string('type');    // scale|bool|select
            $table->smallInteger('min_value')->nullable(); // scale
            $table->smallInteger('max_value')->nullable(); // scale
            $table->jsonb('options_json')->nullable();     // select
            $table->boolean('is_active')->default(true);
            $table->timestampTz('active_from')->nullable();
            $table->timestampTz('active_to')->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->timestampsTz();

            $table->index(['company_id', 'emotion_id']);
            $table->unique(['company_id', 'key']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
