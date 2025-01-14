<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Attempt;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;

class AttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $department = auth()->user()->department; // User ka department
        $semester = auth()->user()->semester; // User ka semester

        // Fetch quizzes with status = 1 and matching department & semester
        $quizzes = Quiz::where('status', 1)
            ->where('department', $department)
            ->where('semester', $semester)
            ->get();

        // Pass quizzes to the view
        return view('attempts.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id); // Quiz details fetch karna
        $question = Question::findOrFail($id);
        // Logged-in user details
        $user = auth()->user();

        // Pass quiz and user details to the view
        return view('attempts.create', [
            'quiz' => $quiz,
            'question' => $question,
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'roll_no' => 'required|string|max:255',
            'question_id' => 'required|exists:questions,id',
        ]);

        // Create a new attempt record
        $attempt = Attempt::create([
            'student_name' => auth()->user()->name,
            'roll_no' => $validated['roll_no'],
            'semester' => auth()->user()->semester,
            'department' => auth()->user()->department,
            'subject_type' => $request->subject_type,
            'subject_name' => $request->subject_name,
            'quiz_id' => $validated['quiz_id'], // Save quiz_id
            'question_id' => $validated['question_id'], // Save question_id
            'status' => 0, // Default status
        ]);

        // Fetch the quiz details
        $quiz = Quiz::findOrFail($validated['quiz_id']);
        $question = Question::findOrFail($validated['question_id']);

        // Prepare student details for the next page
        $studentDetails = [
            'name' => $attempt->student_name,
            'roll_no' => $attempt->roll_no,
            'department' => $attempt->department,
            'semester' => $attempt->semester,
            'subject_type' => $attempt->subject_type,
            'subject_name' => $attempt->subject_name,
        ];

        // Redirect to a new view with quiz and student details
        return view('attempts.show', compact('quiz', 'question', 'studentDetails'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view("attempts.show");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}