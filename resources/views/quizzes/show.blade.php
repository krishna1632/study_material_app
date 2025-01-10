@extends('layouts.admin')

@section('title', 'Quiz Details')

@section('content')
    <h1 class="mt-4">Quiz Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Quizzes</a></li>
        <li class="breadcrumb-item active">Quiz Details</li>
    </ol>

    <!-- Quiz Details Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Quiz Information
            <a href="{{ route('quizzes.index') }}"
                class="btn btn-secondary btn-sm position-absolute top-0 end-0 mt-1 me-2">Back
                to Quizzes</a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Subject Type:</strong> {{ $quiz->subject_type }}
            </div>
            <div class="mb-3">
                <strong>Department:</strong> {{ $quiz->department }}
            </div>
            <div class="mb-3">
                <strong>Semester:</strong> {{ $quiz->semester }}
            </div>
            <div class="mb-3">
                <strong>Subject Name:</strong> {{ $quiz->subject_name }}
            </div>
            <div class="mb-3">
                <strong>Faculty Name:</strong> {{ $quiz->faculty_name }}
            </div>
            <div class="mb-3">
                <strong>Date:</strong> {{ $quiz->date }}
            </div>
            <div class="mb-3">
                <strong>Start Time:</strong> {{ $quiz->start_time }}
            </div>
            <div class="mb-3">
                <strong>End Time:</strong> {{ $quiz->end_time }}
            </div>
        </div>
    </div>

    <!-- Questions List Section -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-1"></i>
            Questions List
        </div>
        <div class="card-body">
            @if ($questions->isEmpty())
                <div class="alert alert-info">
                    No questions available for this quiz.
                </div>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $question->question_text }}</td>
                                <td>
                                    <ul>
                                        @foreach (json_decode($question->options, true) as $key => $value)
                                            <li>{{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @php
                                        $options = json_decode($question->options, true);
                                        $correctOptionIndex = $question->correct_option - 1; // Adjust for 0-based index
                                        $correctOption = $options[$correctOptionIndex] ?? 'N/A';
                                    @endphp
                                    {{ $correctOption }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
