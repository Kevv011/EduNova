<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Http\Resources\CourseResource;

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
            $course = Course::where('instructor_id', $instructorId)->with('students')->get();

            return Inertia::render('Dashboard', [
                'courses' => $course,
            ]);
        } else {
            return Inertia::render('Dashboard');
        }
    }
}
