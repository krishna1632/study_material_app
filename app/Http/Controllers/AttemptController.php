<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttemptDetails;
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
    public function create($id)
    {
        // Fetch quiz details
        $quiz = Quiz::findOrFail($id);

        // Get the logged-in user details
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
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'roll_no' => 'required|string|max:50',
        ]);

        try {
            $attempt = AttemptDetails::create([
                'student_id' => auth()->id(),
                'quiz_id' => $request->quiz_id,
                'roll_no' => $request->roll_no,
                'status' => 0,
            ]);

            return redirect()->route('attempts.show', ['id' => $attempt->id])
                ->with('success', 'Quiz attempt saved successfully.');
        } catch (\Illuminate\Database\QueryException $exception) {
            // Check if it's a unique constraint violation
            if ($exception->getCode() === '23000') { // MySQL code for integrity constraint violation
                return redirect()->back()
                    ->withErrors(['already_submitted' => 'You have already attempted this quiz with the provided roll number.']);
            }

            throw $exception;
        }
    
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Fetch the attempt details
    $attempt = AttemptDetails::with(['quiz', 'student'])->findOrFail($id);

    // Pass data to the view
    return view('attempts.show', [
        'attempt' => $attempt,
        'quiz' => $attempt->quiz,
        'studentDetails' => [
            'name' => $attempt->student->name,
            'roll_no' => $attempt->roll_no,
            'department' => $attempt->student->department,
            'semester' => $attempt->student->semester,
        ],
    ]);
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