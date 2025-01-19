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

        $currentDateTime = now();

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
            // Check if the student has already submitted this quiz
            $existingAttempt = AttemptDetails::where('quiz_id', $request->quiz_id)
                ->where('roll_no', $request->roll_no)
                ->where('status', 1) // Check if status is submitted (1)
                ->first();

            if ($existingAttempt) {
                return redirect()->route('attempts.show', ['id' => $existingAttempt->id])
                    ->with([
                        'alert' => true,
                        'message' => 'This student details is already submitted.',
                        'redirect' => route('attempts.show', ['id' => $existingAttempt->id]), // Redirect to attempts.show
                    ]);
            }

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

        // Check if the user has already submitted this test
        $attempt = AttemptDetails::where('quiz_id', $quizId)
            ->where('student_id', auth()->id()) // Ensure the current user's attempt is checked
            ->where('status', 1) // Check for submitted status
            ->first();

        if ($attempt) {
            // Test already submitted, show SweetAlert and redirect
            return response()->json([
                'alert' => true,
                'message' => 'You have already submitted this quiz. Redirecting to attempts list.',
                'redirect' => route('attempts.index'),
            ]);
        }

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
        $quiz = Quiz::findOrFail($quizId);
        $submittedAnswers = $request->input('answers', []); // Empty if auto-submitted

        // Check if the attempt is already submitted
        $existingAttempt = AttemptDetails::where('quiz_id', $quizId)
            ->where('status', 1)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('attempts.index')
                ->with('error', 'You have already submitted this quiz.');
        }

        // Fetch or create attempt record
        $attempt = AttemptDetails::firstOrCreate(
            ['quiz_id' => $quizId],
            ['status' => 0]
        );

        // Fetch or create attempt record
        $attempt = AttemptDetails::firstOrCreate(
            ['quiz_id' => $quizId, 'student_id' => auth()->id()],
            ['status' => 0]
        );

        // Save responses
        foreach ($submittedAnswers as $questionId => $selectedOption) {
            AttemptQuizDetails::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionId],
                ['selected_option' => $selectedOption]
            );
        }

        // Mark attempt as submitted
        $attempt->update(['status' => 1]);

        // Redirect to results
        return redirect()->route('attempts.results', ['quizId' => $quizId])
            ->with('success', 'Quiz submitted successfully.');
    }


    public function results(Request $request, $quizId)
    {
        // Fetch quiz with its questions
        $quiz = Quiz::with('questions')->findOrFail($quizId);

        // Get the student's attempt for this quiz
        $attempt = AttemptDetails::where('quiz_id', $quizId)
            ->where('student_id', auth()->id()) // Ensure correct user
            ->first();

        if (!$attempt) {
            return redirect()->route('attempts.index')->with('error', 'Test attempt not found.');
        }

        // Get all responses for this attempt
        $responses = AttemptQuizDetails::where('attempt_id', $attempt->id)->get();

        // Initialize variables
        $correctAnswersCount = 0;
        $totalQuestions = $quiz->questions->count();

        // Check each question's response
        foreach ($quiz->questions as $question) {
            $response = $responses->firstWhere('question_id', $question->id);

            // If response exists and matches the correct option
            if ($response && strtolower($response->selected_option) === strtolower($question->correct_option)) {
                $correctAnswersCount++;
            }
        }

        // Calculate the score
        $score = $correctAnswersCount * ($quiz->weightage ?? 1); // Default weightage = 1

        // Pass data to the results view
        return view('attempts.results', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'totalQuestions' => $totalQuestions,
            'correctAnswersCount' => $correctAnswersCount,
            'score' => $score,
            'responses' => $responses, // Pass responses to the view
        ]);
    }

    public function responses($attemptId)
    {
        $attempt = AttemptDetails::with(['quiz', 'quiz.questions'])->findOrFail($attemptId);

        // Get all responses for this attempt
        $responses = AttemptQuizDetails::where('attempt_id', $attempt->id)->get();

        // Initialize variables for score calculation
        $correctAnswersCount = 0;

        $questionsWithResponses = $attempt->quiz->questions->map(function ($question) use ($responses, &$correctAnswersCount) {
            $response = $responses->firstWhere('question_id', $question->id);

            $isCorrect = isset($response) && strtolower($response->selected_option) === strtolower($question->correct_option);

            if ($isCorrect) {
                $correctAnswersCount++;
            }

            return [
                'question_text' => $question->question_text,
                'options' => json_decode($question->options),
                'selected_option' => $response->selected_option ?? null,
                'correct_option' => $question->correct_option,
                'is_correct' => $isCorrect,
            ];
        });

        // Calculate score based on correct answers and quiz weightage
        $score = $correctAnswersCount * ($attempt->quiz->weightage ?? 1);

        return view('attempts.responses', [
            'attempt' => $attempt,
            'questionsWithResponses' => $questionsWithResponses,
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