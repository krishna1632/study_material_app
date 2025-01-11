<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\User;
use App\Models\Subject;
use Carbon\Carbon;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $quizzes = Quiz::with('questions')->get();
        // return view('quizzes.index', compact('quizzes'));


        $user = auth()->user(); // Currently logged-in user
        $department = $user->department;
        $semester = $user->semester;
        $faculty_name = $user->name;
        $roles = $user->getRoleNames();

        
        if ($roles->contains('SuperAdmin') || $roles->contains('Admin')) {
            $quizzes = Quiz::all();
        } elseif ($roles->contains('Faculty')) {
            
            $quizzes = Quiz::where(function ($query) use ($department) {
                $query->where('department', $department)
                    ->orWhere('department', 'ELECTIVE');
            })
                ->where('faculty_name', $faculty_name)
                ->get();
        } else {
            
            $quizzes = Quiz::where('department', $department)
                ->where('semester', $semester)
                ->get();
        }

        return view('quizzes.index', compact('quizzes', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user(); // Currently logged-in user
        $roles = $user->getRoleNames(); // Fetch assigned roles for the user

        // Filter faculties based on role
        if ($roles->contains('Admin') || $roles->contains('SuperAdmin')) {
            // If Admin or SuperAdmin, fetch all users with the "Faculty" role
            $faculties = User::role('Faculty')->get(); // Assuming you use Spatie Roles
        } elseif ($roles->contains('Faculty')) {
            // If the user is a faculty, show only their own data
            $faculties = collect([$user]); // Wrap in a collection for consistency
        } else {
            // If the user doesn't have access, return an empty collection
            $faculties = collect();
        }

        // Determine departments
        if ($roles->contains('Admin') || $roles->contains('SuperAdmin')) {
            // If Admin or SuperAdmin, show all departments
            $departments = [
                'Applied Psychology',
                'Computer Science',
                'B.voc(Software Development)',
                'Economics',
                'English',
                'Environmental Studies',
                'Commerce',
                'Punjabi',
                'Hindi',
                'History',
                'Management Studies',
                'Mathematics',
                'Philosophy',
                'Physical Education',
                'Political Science',
                'Statistics',
                'B.voc(Banking Operations)',
                'ELECTIVE',
            ];
        } else {
            // If the user has other roles, show only their department
            $departments = [$user->department, 'ELECTIVE'];
        }

        // Initial empty subject list
        $subjects = [];

        return view('quizzes.create', compact('departments', 'subjects', 'faculties', 'roles'));
        // return view('quizzes.create');
    }

   
    public function filter_Subjects(Request $request)
    {
        $validated = $request->validate([
            'subject_type' => 'required|string',
            'department' => 'required|string',
            'semester' => 'required|integer',
        ]);

        // Fetch the subjects based on the provided filters
        $subjects = Subject::where('subject_type', $validated['subject_type'])
            ->where('department', $validated['department'])
            ->where('semester', $validated['semester'])
            ->get();

        if ($subjects->isEmpty()) {
            return response()->json([], 404); // Return an empty array with a 404 status if no subjects found
        }

        // Map the subjects to return only the id and name
        $subjectData = $subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->subject_name,
            ];
        });

        return response()->json($subjectData); // Return the subject data as JSON
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $request->validate([
            'subject_type' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'faculty_name' => 'required|string|max:255',
            'date' => "required|date|after_or_equal:$currentDate",
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) use ($currentDate, $currentTime, $request) {
                    if ($request->date === $currentDate && $value < $currentTime) {
                        $fail('Start time must be equal to or after the current time for today.');
                    }
                },
            ],
            'end_time' => [
                'required',
                function ($attribute, $value, $fail) use ($currentDate, $currentTime, $request) {
                    if ($request->date === $currentDate && $value < $currentTime) {
                        $fail('End time must be equal to or after the current time for today.');
                    }
                    if ($value <= $request->start_time) {
                        $fail('End time must be after the start time.');
                    }
                },
            ],
        ]);

        $quiz = Quiz::create($request->all());
        return redirect()->route('questions.index', ['quiz' => $quiz->id])->with('success', 'Quiz created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the quiz and its related questions
        $quiz = Quiz::findOrFail($id);  // Ensure quiz exists
        $questions = Question::where('quiz_id', $id)->get();  // Get questions related to the quiz

        // Return the view with quiz details and questions
        return view('quizzes.show', compact('quiz', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quiz = Quiz::findOrFail($id);  // Fetch quiz by ID
        return view('quizzes.edit', compact('quiz'));  // Return the edit view with quiz details
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $quiz = Quiz::findOrFail($id);  // Fetch quiz by ID

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        // Validate incoming data
        $request->validate([
            'subject_type' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'faculty_name' => 'required|string|max:255',
            'date' => "required|date|after_or_equal:$currentDate",
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) use ($currentDate, $currentTime, $request) {
                    if ($request->date === $currentDate && $value < $currentTime) {
                        $fail('Start time must be equal to or after the current time for today.');
                    }
                },
            ],
            'end_time' => [
                'required',
                function ($attribute, $value, $fail) use ($currentDate, $currentTime, $request) {
                    if ($request->date === $currentDate && $value < $currentTime) {
                        $fail('End time must be equal to or after the current time for today.');
                    }
                    if ($value <= $request->start_time) {
                        $fail('End time must be after the start time.');
                    }
                },
            ],
        ]);

        // Update the quiz
        $quiz->update($request->all());

        return redirect()->route('quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::findOrFail($id);  // Fetch quiz by ID
        $quiz->delete();  // Delete the quiz

        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
    }
}