<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attempt;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;

class AttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $quiz_id)
    {
        dd($quiz_id);  // Check if quiz_id is received correctly
        $user = auth()->user(); // Logged-in user

        if ($user->hasRole('student')) {
            $quiz = Quiz::with('questions')->findOrFail($quiz_id);

            // Only proceed if the quiz is active (status = 1)
            if ($quiz->status !== 1) {
                return redirect()->route('quizzes.index')->with('error', 'The quiz is not active yet.');
            }

            // return view('attempts.index', compact('quiz'));
        }
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