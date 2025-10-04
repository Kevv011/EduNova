<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateLessonRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\updateLessonContentRequest;

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

    public function updateContent(UpdateLessonContentRequest $request, Lesson $lesson)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // Actualizar campos
            $lesson->update([
                'subtitle' => $data['subtitle'] ?? null,
                'content' => $data['content'],
            ]);

            // Guardar URLs de recursos (si existen)
            if (!empty($data['url_resource'])) {
                $urls = array_filter(
                    array_map('trim', $data['url_resource']),
                    fn($url) => !empty($url)
                );
                $lesson->url_resource = array_unique($urls);
                $lesson->save();
            }

            // Eliminar archivos marcados para eliminar
            if (!empty($data['remove_media_ids'])) {
                foreach ($data['remove_media_ids'] as $mediaId) {
                    $media = $lesson->media()->find($mediaId);
                    if ($media) {
                        $media->delete();
                    }
                }
            }

            // Subir nuevos archivos a Media Library
            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $lesson->addMedia($file)
                        ->toMediaCollection('lessons');
                }
            }

            DB::commit();

            return redirect()
                ->route('modules.lessons', $lesson->module_id)
                ->with('flash', [
                    'bannerStyle' => 'success',
                    'banner' => 'Contenido de la lección actualizado correctamente.',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Hubo un error al actualizar el contenido: ' . $e->getMessage()]);
        }
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
