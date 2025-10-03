<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateLessonRequest;

class LessonController extends Controller
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
    public function store(StoreUpdateLessonRequest $request)
    {
        $lessonData = $request->validated();

        $lesson = Lesson::create($lessonData);

        return redirect()
            ->route('modules.lessons', $lessonData['module_id'])
            ->with('success', 'Lección creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateLessonRequest $request, Lesson $lesson)
    {
        $lessonData = $request->validated();

        $lesson->update($lessonData);

        return redirect()
            ->route('modules.lessons', $lesson->module_id)
            ->with('success', 'Lección actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->back();
    }
}
