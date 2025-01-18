@extends('layouts.admin')

@section('title', 'Quiz Results')

@section('content')
    <h1 class="mt-4">Quiz Results: {{ $quiz->subject_name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Attempt Quiz</a></li>
        <li class="breadcrumb-item active">Quiz Results</li>
    </ol>

    <div class="card shadow-lg border-0">
        <div class="card shadow-lg border-0 p-4">
            <div class="card-body">
                <h3 class="fw-bold text-primary text-center mb-4">Test Completed!</h3>


                <!-- Horizontal Student Details -->
                <div class="d-flex justify-content-between border-bottom pb-3 mb-4">
                    <p><strong>Student Name:</strong> {{ $attempt->student->name }}</p>
                    <p><strong>Roll Number:</strong> {{ $attempt->roll_no }}</p>
                    <p><strong>Total Questions:</strong> {{ $totalQuestions }}</p>
                    <p><strong>Correct Answers:</strong> {{ $correctAnswersCount }} / {{ $totalQuestions }}</p>
                </div>

                <!-- Score Section -->
                <div class="text-center mb-4">
                    <h1 class="display-3 fw-bold text-success">Your Score: {{ $score }}</h1>
                </div>

                <!-- Buttons Section -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('attempts.responses', ['attemptId' => $attempt->id]) }}" class="btn btn-primary">View
                        Responses</a>
                    <a href="{{ route('attempts.index') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection
