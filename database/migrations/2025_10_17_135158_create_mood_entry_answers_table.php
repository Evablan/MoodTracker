<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mood_entry_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mood_entry_id')->constrained('mood_entries')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            // Rellena solo uno según el tipo de la pregunta
            $table->smallInteger('answer_numeric')->nullable();   // scale
            $table->boolean('answer_bool')->nullable();           // bool
            $table->string('answer_option_key')->nullable();      // select
            $table->timestampsTz();

            $table->index(['mood_entry_id', 'question_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('mood_entry_answers');
    }
};
