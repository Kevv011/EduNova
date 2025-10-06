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
use App\Http\Resources\ModuleResource;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\Module;
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

    public function students(Course $course)
    {
        return Inertia::render('Courses/Students', [
            'course' => new CourseResource($course->load(['category', 'instructor', 'students'])),
        ]);
    }

    public function modules(Course $course)
    {
        $modules = Module::with('lessons')
            ->where('course_id', $course->id)
            ->get();

        return Inertia::render('Courses/Modules', [
            'course' => new CourseResource($course->load(['category', 'instructor', 'students'])),
            'modules' => ModuleResource::collection($modules),
        ]);
    }

    // Rutas para estudiantes
    public function studentIndex()
    {
        $courses = Course::with('students', 'instructor.user', 'category')->get();

        return Inertia::render('Courses/Students/Index', [
            'courses' => CourseResource::collection($courses),
            'categories' => CategoryResource::collection(Category::all()),
        ]);
    }

    public function studentCourse(Course $course)
    {
        return Inertia::render('Courses/Students/Course', [
            'course' => new CourseResource($course->load(['category', 'instructor.user', 'students', 'modules.lessons'])),
        ]);
    }

    public function studentInscription(Request $request)
    {
        // Validar el request
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        // Obtener el estudiante autenticado
        $student = auth()->user()->student;

        if (!$student) {
            return back()->withErrors(['student_id' => 'No se encontró el perfil de estudiante.']);
        }

        // Verificar si ya está inscrito
        $alreadyEnrolled = $student->courses()->where('course_id', $validated['course_id'])->exists();

        if ($alreadyEnrolled) {
            return back()->withErrors(['course_id' => 'Ya estás inscrito en este curso.']);
        }

        // Inscribir al estudiante
        $student->courses()->attach($validated['course_id'], [
            'incription_date' => now(),
            'progress' => 0,
            'status_progress' => 'in_progress',
            'grade' => null,
        ]);

        return redirect()->back()->with('success', 'Te has inscrito exitosamente al curso.');
    }

    public function studentMyCourses()
    {
        $student = auth()->user()->student;

        $myCourses = $student
            ? $student->courses()->with('category', 'instructor.user', 'students')->get()
            : collect();

        return Inertia::render('Courses/Students/MyCourses', [
            'courses' => CourseResource::collection($myCourses),
        ]);
    }

    public function studentModules(Course $course)
    {
        $modules = Module::with('lessons')
            ->where('course_id', $course->id)
            ->get();

        return Inertia::render('Courses/Students/Modules', [
            'course' => new CourseResource($course->load(['category', 'instructor.user'])),
            'modules' => ModuleResource::collection($modules),
        ]);
    }
}
