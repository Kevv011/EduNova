<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructorId = auth()->user()?->instructor?->id;

        if ($instructorId) {
            $courses = Course::where('instructor_id', $instructorId)->get();    // Obtener cursos del instructor
            $categoryIds = $courses->pluck('category_id')->unique()->values();  // Obtener categorias de esos cursos
            $categories = Category::whereIn('id', $categoryIds)->get();         // En base a categorias de cursos del intructor, obtenerlas
        }

        return Inertia::render('Categories/Index', [
            'courses' => $courses,
            'categories' => $categories,
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
