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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'consent_at')) {
                $table->timestamp('consent_at')->nullable()->after('email_verified_at');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('employee')->after('consent_at'); // útil para el control de acceso
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'consent_at')) {
                $table->dropColumn('consent_at');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
