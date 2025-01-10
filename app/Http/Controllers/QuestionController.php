<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource for a specific quiz.
     */
    public function index($quizId)
    {
        $quiz = Quiz::findOrFail($quizId); // Ensure quiz exists
        $questions = Question::where('quiz_id', $quizId)->get(); // Fetch questions for the quiz
        return view('questions.index', compact('quiz', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($quizId)
    {
        $quiz = Quiz::findOrFail($quizId); // Ensure quiz exists
        return view('questions.create', compact('quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'questions' => 'required|array', // Multiple questions validation
            'questions.*.question_text' => 'required|string|max:255',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.correct_option' => 'required|integer|min:1',
        ]);

        // Loop through each question and save it
        foreach ($request->questions as $questionData) {
            Question::create([
                'quiz_id' => $request->quiz_id,
                'question_text' => $questionData['question_text'],
                'options' => json_encode($questionData['options']), // Convert options array to JSON
                'correct_option' => $questionData['correct_option'],
            ]);
        }

        return response()->json(['message' => 'Questions saved successfully!'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($quizId, $id)
    {
        $quiz = Quiz::findOrFail($quizId); // Ensure quiz exists
        $question = Question::findOrFail($id); // Find the question by ID

        // Decode the options JSON to be used in the view
        $question->options = json_decode($question->options, true);

        return view('questions.edit', compact('quiz', 'question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $quizId, $id)
    {
        // Validate the request
        $request->validate([
            'question_text' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|string',
        ]);

        // Find the question and update it
        $question = Question::findOrFail($id);

        $options = $request->input('options');
        $correctOption = $request->input('correct_option');

        // Ensure correct_option is valid
        if ($correctOption < 1 || $correctOption > count($options)) {
            return back()->withErrors(['correct_option' => 'The selected correct option is invalid.']);
        }

        // Update the question
        $question->update([
            'question_text' => $request->question_text,
            'options' => json_encode($options), // Convert options array to JSON
            'correct_option' => $correctOption,
        ]);

        // Redirect to index with success message
        return redirect()->route('questions.index', $quizId)
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $quizId = $question->quiz_id; // Save quiz ID to redirect later
        $question->delete();

        return redirect()->route('questions.index', $quizId)
            ->with('success', 'Question deleted successfully!');
    }

    public function submitQuestions($quizId)
    {
        // Get all questions for the quiz
        $questions = Question::where('quiz_id', $quizId)->get();

        // Mark all questions as submitted
        foreach ($questions as $question) {
            $question->is_submitted = 1; // Mark as submitted
            $question->save();
        }

        // Add a session flash message for finalized quiz
        session()->flash('finalized_quiz_id', $quizId);

        // Redirect to quizzes index
        return redirect()->route('quizzes.index')->with('success', 'All questions for this quiz have been submitted.');
    }
}