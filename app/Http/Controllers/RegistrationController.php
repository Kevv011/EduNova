<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreAcademicInfoRequest;
use App\Http\Requests\StoreInterestInfoRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Registration/RegistrationData');
    }

    // Paso 2; Actualizacion de informacion academica
    public function academic_information(StoreAcademicInfoRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($validatedData['user_id']);

        // Marcar el registro al siguiente paso
        $user->update([
            'register_completed' => 1,
        ]);

        Student::create($validatedData);

        return Redirect::route('registration.academic-interests')
            ->with('success', 'Estudiante creado correctamente');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function academic_interest()
    {
        return Inertia::render('Registration/RegisterInterests');
    }

    public function interests_info(StoreInterestInfoRequest $request)
    {
        try {
            // Obtener los datos validados
            $validatedData = $request->validated();

            $user = User::findOrFail($validatedData['user_id']);

            $student = Student::where('user_id', $user->id)->firstOrFail();

            $studentData = collect($validatedData)->except('user_id')->toArray();
            $student->update($studentData);

            // Marcar el registro al siguiente paso (completado)
            $user->update([
                'register_completed' => 2,
            ]);

            return Redirect::route('dashboard')
                ->with('success', 'Registro completado exitosamente. ¡Bienvenido a EduNova!');
        } catch (\Exception $e) {
            return Redirect::back()
                ->withInput()
                ->with('error', 'Error al completar el registro. Intenta nuevamente.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
