<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizReport;
use App\Models\Subject;
use App\Models\User;
use App\Models\AttemptDetails;
use App\Models\Quiz;
use App\Models\AttemptQuizDetails;


class QuizReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        return view('quiz_reports.index', compact('departments', 'subjects', 'faculties', 'roles'));
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

    public function fetchQuizzes(Request $request)
    {
        $validated = $request->validate([
            'subject_type' => 'required|string',
            'department' => 'required|string',
            'semester' => 'required|integer',
            'subject_name' => 'required|string',
            'faculty_name' => 'required|string',
        ]);

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

        // Fetch quizzes based on the filters provided
        $quizzes = Quiz::where('subject_type', $validated['subject_type'])
            ->where('department', $validated['department'])
            ->where('semester', $validated['semester'])
            ->where('subject_name', $validated['subject_name'])
            ->where('faculty_name', $validated['faculty_name'])
            ->where('status', 1) // Filter quizzes with status 1
            ->get();

        // Fetch attempt details with status = 1 (filter for quizzes that have been attempted)
        $attemptedQuizzes = AttemptDetails::where('status', 1)
            ->whereIn('quiz_id', $quizzes->pluck('id')) // Ensure the quizzes are filtered based on quiz_id
            ->get();

        // Return the view with filtered quizzes and attempted quizzes
        return view('quiz_reports.index', compact('quizzes', 'attemptedQuizzes', 'faculties', 'roles', 'departments'));
    }

    public function viewResults($quiz_id)
    {
        // Fetch all students who have attempted the quiz
        $attempts = AttemptDetails::where('quiz_id', $quiz_id)
            ->where('status', 1)
            ->get();

        // Fetch the quiz with related questions
        $quiz = Quiz::with('questions')->find($quiz_id);

        if (!$quiz) {
            return redirect()->back()->withErrors('Quiz not found.');
        }

        $studentsResults = [];

        foreach ($attempts as $attempt) {
            // Fetch student details
            $student = User::find($attempt->student_id);

            if (!$student) {
                continue; // Skip if student details are not found
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
            $marks = $correctAnswersCount * ($quiz->weightage ?? 1);

            // Add student's result to the results array
            $studentsResults[] = [
                'name' => $student->name,
                'roll_no' => $attempt->roll_no,
                'semester' => $student->semester,
                'department' => $student->department,
                'marks' => $marks,
            ];
        }

        return view('quiz_reports.results', compact('studentsResults', 'quiz_id'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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