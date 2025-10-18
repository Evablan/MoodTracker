<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete(); // NULL = global
            $table->string('key');
            $table->string('name');
            $table->string('color_hex')->default('#888888');
            $table->smallInteger('valence'); // -2..2
            $table->smallInteger('arousal'); // 1..5
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestampsTz();

            $table->unique(['company_id', 'key']);
        });

        DB::statement("ALTER TABLE emotions ADD CONSTRAINT chk_emotions_valence CHECK (valence BETWEEN -2 AND 2)");
        DB::statement("ALTER TABLE emotions ADD CONSTRAINT chk_emotions_arousal CHECK (arousal BETWEEN 1 AND 5)");
    }

    public function down(): void
    {
        Schema::dropIfExists('emotions');
    }
};
