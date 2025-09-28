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
        Schema::table('instructors', function (Blueprint $table) {
            $table->string('academic_level')->nullable();       // Nivel academico del instructor
            $table->json('subject_specialties')->nullable();    // Areas de especializacion
            $table->json('schedule_availability')->nullable();  // Disponibilidad de horarios
            $table->string('subjects')->nullable();             // Materias que imparte
            $table->json('level_education')->nullable();        // Ensenianza dedicada a (Tercer ciclo, bachillerato, universitario, etc)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn('academic_level');
            $table->dropColumn('subject_specialties');
            $table->dropColumn('schedule_availability');
            $table->dropColumn('subjects');
            $table->dropColumn('level_education');
        });
    }
};
