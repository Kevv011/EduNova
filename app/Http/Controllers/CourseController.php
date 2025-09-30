<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\CourseResource;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\CourseFilter;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\InstructorResource;
use App\Models\Category;
use App\Models\Instructor;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $instructor_id = Instructor::where('user_id', auth()->id())->value('id');

        return Inertia::render('Courses/Index', [
            'courses' => CourseResource::collection(
                QueryBuilder::for(Course::class)
                    ->allowedFilters([
                        AllowedFilter::custom('course_name', new CourseFilter),
                    ])
                    ->where('instructor_id', $instructor_id)
                    ->with(['category', 'instructor', 'students'])
                    ->allowedSorts('id', 'created_at')
                    ->defaultSort('-id')
                    ->paginate()
            ),
            'filters' => $request->all('filter', 'sort'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Courses/UpdateOrCreate', [
            'categories' => CategoryResource::collection(Category::all()),
            'instructor' => new InstructorResource(
                Instructor::where('user_id', auth()->id())->first()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $courseData = $request->validated();

        $course = Course::create($courseData);

        if ($request->hasFile('image')) {
            $course->addMedia($request->file('image'))->toMediaCollection('course_images');
        }

        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return Inertia::render('Courses/Show', [
            'course' => new CourseResource($course->load(['category', 'instructor', 'students'])),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return Inertia::render('Courses/UpdateOrCreate', [
            'course' => new CourseResource($course->load('category')),
            'categories' => CategoryResource::collection(Category::all()),
            'instructor' => new InstructorResource(
                Instructor::where('user_id', auth()->id())->first()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->except('image'));

        if ($request->hasFile('image')) {
            $course->clearMediaCollection('course_images');

            $course->addMedia($request->file('image'))->toMediaCollection('course_images');
        }

        return redirect()->route('courses.index')->with('success', 'Curso actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
