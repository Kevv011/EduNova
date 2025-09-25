<?php

namespace App\Http\Controllers;

use App\Models\InstructorRequest;
use App\Http\Requests\StoreInstructorRequestRequest;
use App\Http\Requests\UpdateInstructorRequestRequest;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $redirect = null;

        if ($id = $request->session()->get('instructor_request_id')) {
            $redirect = InstructorRequest::find($id);
        }

        return Inertia::render('InstructorRequest/InstructorProfile', [
            'instructorRequest' => $redirect?->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequestRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('profile_photo_path')) {
            $photo = $request->file('profile_photo_path');

            // Generar nombre único para la imagen
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

            // Guardar en storage/app/public/instructor-photos
            $photoPath = $photo->storeAs('instructor-photos', $photoName, 'public');

            // Guardar la ruta en la base de datos
            $validatedData['profile_photo_path'] = $photoPath;
        }

        // Crear la solicitud y redireccionar
        $instructorRequest = InstructorRequest::create($validatedData);

        session()->put('instructor_request_id', $instructorRequest->id);

        return redirect()
            ->route('instructor-requests.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(InstructorRequest $instructorRequest)
    {
        return Inertia::render('InstructorRequest/AcademicInformation');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstructorRequest $instructorRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequestRequest $request, InstructorRequest $instructorRequest)
    {
        // Validar campos con form request
        $validatedData = $request->validated();

        // Manejar certificaciones con Media Library
        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $index => $certificate) {

                try {
                    $instructorRequest
                        ->addMedia($certificate)
                        ->usingName("Certificado " . ($index + 1))
                        ->usingFileName($certificate->getClientOriginalName())
                        ->toMediaCollection('instructor_certificates');
                } catch (\Exception $error) {
                    \Log::error("Error procesando certificado: " . $error->getMessage());
                }
            }
        }

        session()->put('instructor_request_id', $instructorRequest->id);

        // Actualizar informacion
        $instructorRequest->update(collect($validatedData)->except('certificates')->toArray());

        // Respuesta y redireccion
        return redirect()
            ->route('instructor-requests.create');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstructorRequest $instructorRequest)
    {
        //
    }
}
