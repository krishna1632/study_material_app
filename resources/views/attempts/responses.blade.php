@extends('layouts.admin')

@section('title', 'View Responses')

@section('content')
    <h1 class="mt-4 text-center">Quiz Responses: {{ $attempt->quiz->subject_name }}</h1>
    <ol class="breadcrumb mb-4 justify-content-center"></ol>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <!-- Display Score -->
            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold text-success">Your Score: {{ $score }}</h1>
            </div>

            <h3 class="fw-bold text-primary text-center mb-4">Your Responses</h3>

            <!-- Questions and Responses -->
            <div id="responsesContainer">
                @foreach ($questionsWithResponses as $question)
                    <div class="mb-5 p-3 border rounded shadow-sm bg-light">
                        <!-- Question Text -->
                        <p class="fw-bold text-dark">
                            <span class="text-primary">Q{{ $loop->iteration }}:</span>
                            {{ $question['question_text'] }}
                        </p>

                        <!-- Options -->
                        <div class="mt-3">
                            @foreach ($question['options'] as $option)
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" disabled
                                        @if ($option === $question['selected_option']) checked @endif>
                                    <label
                                        class="form-check-label text-dark {{ $option === $question['selected_option'] ? ($question['is_correct'] ? 'text-success fw-bold' : 'text-danger fw-bold') : '' }}">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Response Details -->
                        <div class="mt-2">
                            @if ($question['is_correct'])
                                <p class="text-success">Your answer is correct!</p>
                            @else
                                <p class="text-danger">Your answer is incorrect.</p>
                                <p class="text-success">Correct Option: {{ $question['correct_option'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('attempts.index', $attempt->quiz->id) }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
@endsection
