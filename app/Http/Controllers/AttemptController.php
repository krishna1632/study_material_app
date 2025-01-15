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
        // Logged-in user details
        $user = auth()->user();

        // Pass quiz and user details to the view
        return view('attempts.create', [
            'quiz' => $quiz,
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
        ]);

        // Save the required fields in the Attempt table
        $attempt = Attempt::create([
            'student_name' => auth()->user()->name, // Logged-in user ka naam
            'roll_no' => $validated['roll_no'], // Roll number
            'semester' => auth()->user()->semester, // User ka semester
            'department' => auth()->user()->department, // User ka department
            'subject_type' => $request->input('subject_type'), // Subject Type
            'subject_name' => $request->input('subject_name'), // Subject Name
            'quiz_id' => $validated['quiz_id'], // Quiz ID
            'status' => 0, // Test status (in progress)
        ]);

        // Fetch quiz details
        $quiz = Quiz::findOrFail($validated['quiz_id']);

        // Manually entered roll number from the validated data
        $rollNo = $validated['roll_no'];

        // Fetch attempt details using manually entered roll number from the attempt table
        $attempt = Attempt::where('roll_no', $rollNo)
            ->where('quiz_id', $validated['quiz_id']) // Ensure the quiz_id matches
            ->first(); // Fetch first matching attempt

        // Prepare student details to pass to the view
        $studentDetails = [
            'name' => auth()->user()->name,
            'roll_no' => $rollNo, // Use the roll number passed from the route
            'department' => auth()->user()->department,
            'semester' => auth()->user()->semester,
        ];

        // After saving, return the view directly with the passed data
        return view('attempts.show', compact('quiz', 'studentDetails', 'attempt'))
            ->with('success', 'Details saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // 
    }

    // Add a new method to fetch quiz questions
    public function startTest(Request $request, $quizId)
    {
        $quiz = Quiz::find($quizId);
        $questions = Question::where('quiz_id', $quizId)->paginate(1);

        // Get answers from session or initialize empty array
        $previousAnswers = session()->get('answers', []);

        // If new answers are submitted, merge them with session
        if ($request->has('answers')) {
            $submittedAnswers = $request->input('answers');
            $previousAnswers = array_merge($previousAnswers, $submittedAnswers);
            session()->put('answers', $previousAnswers);
        }

        // Get the current question ID from the request
        $currentQuestionId = $request->input('question_id') ?? $questions->first()->id;

        return view('attempts.start_test', compact('quiz', 'questions', 'previousAnswers', 'currentQuestionId'));
    }

    public function storeAnswers(Request $request)
    {
        // Validate and store answers in session
        $request->validate([
            'answers' => 'required|array',
        ]);

        // Save the answers in session
        session()->put('answers', $request->input('answers'));

        return response()->json(['success' => true]);
    }

    public function submitTest(Request $request, $quizId)
    {
        // Validate that answers are provided
        $request->validate([
            'answers' => 'required|array', // Ensure 'answers' is an array
        ]);

        // Iterate through the selected answers and save
        foreach ($request->input('answers') as $questionId => $answer) {
            // Save the 'answers' and 'question_id' for each quiz and question
            Attempt::create([
                'quiz_id' => $quizId,             // Match the quiz_id
                'question_id' => $questionId,     // Save the question_id
                'answers' => $answer,             // Save the selected answer
            ]);
        }

        return redirect()->route('attempts.index', $quizId)
            ->with('success', 'Test submitted successfully!');
    }






    public function results(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $user = auth()->user();

        // Fetch the attempt for this user and quiz
        $attempt = Attempt::where('quiz_id', $quizId)
            ->where('roll_no', $user->roll_no)
            ->first();

        if (!$attempt) {
            return redirect()->route('attempts.index')->with('error', 'Test attempt not found.');
        }

        return view('attempts.results', compact('quiz', 'attempt'));
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