<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Category;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CategoryResource;

class LandingController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome');
    }

    public function dashboard()
    {
        $instructorId = auth()->user()?->instructor?->id;

        if ($instructorId) {
            $course = Course::where('instructor_id', $instructorId)->with('students')
                ->inRandomOrder()
                ->limit(10)
                ->get();

            return Inertia::render('Dashboard', [
                'courses' => $course,
            ]);
        } else {
            $courses = Course::with('students', 'instructor.user')
                ->inRandomOrder()
                ->limit(10)
                ->get();

            $categories = Category::all();

            return Inertia::render('Dashboard', [
                'coursesStudent' => CourseResource::collection($courses),
                'categories' => CategoryResource::collection($categories),
            ]);
        }
    }
}
