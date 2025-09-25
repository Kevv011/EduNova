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
        Schema::create('instructor_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            $table->text('biography');                          // Biografia
            $table->string('academic_level')->nullable();       // Nivel academico del instructor
            $table->json('subject_specialties')->nullable();    // Areas de especializacion
            $table->json('schedule_availability')->nullable();  // Disponibilidad de horarios
            $table->string('subjects')->nullable();             // Materias que imparte
            $table->json('level_education')->nullable();        // Ensenianza dedicada a (Tercer ciclo, bachillerato, universitario, etc)

            $table->string('status')->default('pending_authorization'); // Estado de solicitud
            $table->text('admin_notes')->nullable();                    // Nota del admin ante aprobacion/rechazo
            $table->text('rejection_reason')->nullable();               // Razon de rechazo (Si existe)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_requests');
    }
};
