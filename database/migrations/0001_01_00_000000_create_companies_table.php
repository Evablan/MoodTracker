<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('default_timezone')->default('Europe/Zurich');
            $table->string('default_locale')->default('es');
            $table->timestampsTz();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
