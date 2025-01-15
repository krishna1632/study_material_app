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

        // Instead of saving to the database, prepare student details
        $studentDetails = [
            'name' => auth()->user()->name,
            'roll_no' => $validated['roll_no'],
            'semester' => auth()->user()->semester,
            'department' => auth()->user()->department,
            'subject_type' => $request->subject_type,
            'subject_name' => $request->subject_name,
        ];

        // Fetch quiz and question details
        $quiz = Quiz::findOrFail($validated['quiz_id']);
        $question = Question::findOrFail($validated['question_id']);

        // Redirect to the show page with quiz and student details
        return view('attempts.show', compact('quiz', 'question', 'studentDetails'));
    }

    // Add a new method to fetch quiz questions
    public function startTest(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questions = Question::where('quiz_id', $quizId)->paginate(1); // Fetch questions for the specific quiz

        // If there are previous answers, get them from the session
        $previousAnswers = $request->session()->get('selected_answers', []);

        // Pass quiz, questions, and previous answers to the view
        return view('attempts.start_test', compact('quiz', 'questions', 'previousAnswers'));
    }

    public function submitTest(Request $request, $quizId)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string', // Ensure every answer is a string
            'roll_no' => 'required|string|max:255',
        ]);


        // Fetch the quiz and related questions
        $quiz = Quiz::findOrFail($quizId);
        $questions = Question::where('quiz_id', $quizId)->get();

        // Save the student's answers and calculate the result
        $correctAnswers = 0;
        foreach ($questions as $question) {
            // Check if the provided answer matches the correct option
            $selectedAnswer = $validated['answers'][$question->id] ?? null;

            // Check if the selected answer is correct
            if ($selectedAnswer && $selectedAnswer == $question->correct_option) {
                $correctAnswers++;
            }

            // Store the answer in the database (in a separate table or a related column if needed)
            // Example: Store answers in a `question_attempts` table or update attempt record if necessary.
            Attempt::create([
                'name' => auth()->user()->name,
                'roll_no' => $validated['roll_no'],
                'semester' => auth()->user()->semester,
                'department' => auth()->user()->department,
                'subject_type' => $request->subject_type,
                'subject_name' => $request->subject_name,
                'quiz_id' => $quizId,
                'question_id' => $question->id,
                'answer' => $selectedAnswer, // Save the selected answer
                'status' => 1, // Mark as completed
            ]);
        }

        // Update the attempt status to 'completed'
        $attempt = Attempt::where('quiz_id', $quizId)
            ->where('roll_no', $validated['roll_no'])
            ->where('status', 0) // Find the attempt that's not completed yet
            ->first();

        if ($attempt) {
            $attempt->status = 1; // Mark the attempt as completed
            $attempt->correct_answers = $correctAnswers; // Store the number of correct answers
            $attempt->save();
        }

        // Redirect to a results page or show a success message
        return redirect()->route('attempts.results', ['quizId' => $quizId])->with('success', 'Test submitted successfully!');
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 
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