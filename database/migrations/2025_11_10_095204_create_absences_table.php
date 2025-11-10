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
        Schema::create('absences', function (Blueprint $table) {
            $table->id('leave_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK a la tabla users, cascadeOnDelete para que se elimine la fila de la tabla absences si se elimina el usuario
            $table->date('start_date');
            $table->date('end_date')->nullable(); //nullable para que se pueda omitir el campo end_date
            $table->string('leave_type')->nullable(); //nullable para que se pueda omitir el campo leave_type, por defecto es 'vacation'
            $table->unsignedInteger('days')->nullable(); //nullable para que se pueda omitir el campo days
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
