<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('email');
            $table->unique(['company_id', 'email']); // único por empresa

            $table->timestampTz('email_verified_at')->nullable();

            // SSO listo
            $table->string('provider')->default('local'); // local|google|microsoft
            $table->string('external_id')->nullable();
            $table->index(['provider', 'external_id']);

            $table->string('status')->default('active'); // active|inactive
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
