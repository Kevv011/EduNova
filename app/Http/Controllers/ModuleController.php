<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateModuleRequest;
use Inertia\Inertia;
use App\Http\Resources\LessonResource;
use App\Http\Resources\ModuleResource;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateModuleRequest $request)
    {
        $moduleData = $request->validated();

        $lastNumber = Module::where('course_id', $moduleData['id_course'])->max('number_module') ?? 0;
        $number_module = $lastNumber + 1;

        Module::create([
            'title' => $moduleData['title'],
            'description' => $moduleData['description'],
            'number_module' => $number_module,
            'course_id' => $moduleData['id_course'],
        ]);

        return redirect()
            ->route('courses.modules', $moduleData['id_course'])
            ->with('success', 'Nuevo modulo: ' . $moduleData['title']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateModuleRequest $request, Module $module)
    {
        $moduleData = $request->validated();

        $module->update($moduleData);

        return redirect()
            ->route('courses.modules', $module->course_id)
            ->with('success', 'Modulo actualizado: ' . $moduleData['title']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $courseId = $module->course_id;
        $module->delete();

        return redirect()
            ->route('courses.modules', $courseId)
            ->with('success', 'Módulo eliminado correctamente.');
    }

    public function disable(Module $module)
    {
        $module->status = 0;
        $module->save();

        return redirect()
            ->route('courses.modules', $module->course_id)
            ->with('success', 'Módulo deshabilitado correctamente.');
    }

    public function enable(Module $module)
    {
        $module->status = 1;
        $module->save();

        return redirect()
            ->route('courses.modules', $module->course_id)
            ->with('success', 'Módulo habilitado correctamente.');
    }

    public function lessons(Module $module)
    {
        $module = Module::with(['lessons', 'course'])->find($module->id);

        return Inertia::render('Lessons/Index', [
            'module' => new ModuleResource($module),
        ]);
    }
}
