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
            <div class="d-flex justify-content-end">
                <a href="{{ route('quizzes.instructions', $quiz->id) }}" class="btn btn-primary btn-sm me-2">Write
                    Instructions</a>

                <!-- Check if questions are finalized -->
                @if ($quiz->questions && $quiz->questions->where('is_submitted', 1)->count() > 0)
                    <!-- If questions are finalized, show Start Test button -->
                    <form id="startTestForm" action="#" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <button type="submit" class="btn btn-danger btn-sm me-2">Start Test</button>
                    </form>
                @endif

                <a href="{{ route('quizzes.index') }}" class="btn btn-secondary btn-sm me-2">Back to Quizzes</a>
            </div>
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
                <strong>Start Time - End Time:</strong>
                {{ \Carbon\Carbon::parse($quiz->start_time)->format('h:i A') }} -
                {{ \Carbon\Carbon::parse($quiz->end_time)->format('h:i A') }}
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

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Handle form submission with SweetAlert confirmation
        document.getElementById('startTestForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: 'Are you sure you want to start the test?',
                text: "Once started, you can't change your answers.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Start Test!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    this.submit();
                }
            });
        });
    </script>
@endsection
