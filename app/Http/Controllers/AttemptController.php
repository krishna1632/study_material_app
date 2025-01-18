<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttemptDetails;
use App\Models\Quiz;
use App\Models\AttemptQuizDetails;


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
        $questions = Question::where('quiz_id', $quizId)->get();

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

   

    public function submitTest(Request $request, $quizId)
{
    // Get the quiz and the logged-in user
    $quiz = Quiz::findOrFail($quizId);
    $user = auth()->user();

    // Fetch the attempt for this user and quiz
    $attempt = AttemptDetails::where('quiz_id', $quizId)
                             ->where('student_id', $user->id)
                             ->first();

    if (!$attempt) {
        return redirect()->route('attempts.index')->with('error', 'Quiz attempt not found.');
    }

    // Fetch the submitted answers
    $submittedAnswers = $request->input('answers');

    // If no answers are submitted, return an error
    if (empty($submittedAnswers)) {
        return redirect()->back()->withErrors(['answers' => 'You must answer all the questions.']);
    }

    // Store answers in attempts_quiz_details table
    foreach ($submittedAnswers as $questionId => $selectedOption) {
        AttemptQuizDetails::create([
            'attempt_id' => $attempt->id,
            'question_id' => $questionId,
            'selected_option' => $selectedOption,
        ]);
    }

    // Update the attempt status to indicate that the quiz has been submitted
    $attempt->status = 1; // 1 means submitted
    $attempt->save();

    // Redirect the user to the results page or any other page
    return redirect()->route('attempts.results', ['quizId' => $quizId])
                     ->with('success', 'Quiz submitted successfully.');
}







public function results(Request $request, $quizId)
{
    // Fetch the quiz details with its questions
    $quiz = Quiz::with('questions')->findOrFail($quizId);
    $user = auth()->user();

    // Fetch the user's attempt for this quiz
    $attempt = AttemptDetails::where('quiz_id', $quizId)
                             ->where('student_id', $user->id)
                             ->first();

    if (!$attempt) {
        return redirect()->route('attempts.index')->with('error', 'Test attempt not found.');
    }

    // Fetch the responses for this attempt
    $responses = AttemptQuizDetails::where('attempt_id', $attempt->id)->get();

    // Initialize variables
    $correctAnswersCount = 0;

    // Loop through the questions and calculate correct answers
    foreach ($quiz->questions as $question) {
        // Find the response for the current question
        $response = $responses->where('question_id', $question->id)->first();

        // Check if the response matches the correct option
        if ($response && $response->selected_option == $question->correct_option) {
            $correctAnswersCount++;
        }
    }

    // Calculate the total score
    $totalQuestions = $quiz->questions->count();
    $score = $correctAnswersCount * $quiz->weightage;

    // Pass data to the view
    return view('attempts.results', [
        'quiz' => $quiz,
        'attempt' => $attempt,
        'totalQuestions' => $totalQuestions,
        'correctAnswersCount' => $correctAnswersCount,
        'score' => $score,
    ]);
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