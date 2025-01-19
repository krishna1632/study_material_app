@extends('layouts.admin')

@section('title', 'Attempt Quiz')

@section('content')
    <h1 class="mt-4">Attempt Quiz</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Attempt Quiz</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-question-circle me-1"></i>
            Quiz Information
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject Type</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Subject Name</th>
                        <th>Faculty Name</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($quizzes->isEmpty())
                        <tr>
                            <td colspan="10" class="text-center">No active quizzes available.</td>
                        </tr>
                    @else
                        @foreach ($quizzes as $index => $quiz)
                            @php
                                // Fetch the attempt details for the current quiz and logged-in user
                                $attempt = $quiz->attemptDetails()->where('student_id', auth()->id())->first();
                                $currentDateTime = now();
                                $quizStartDateTime = \Carbon\Carbon::parse($quiz->date . ' ' . $quiz->start_time);
                                $quizEndDateTime = \Carbon\Carbon::parse($quiz->date . ' ' . $quiz->end_time);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $quiz->subject_type }}</td>
                                <td>{{ $quiz->department }}</td>
                                <td>{{ $quiz->semester }}</td>
                                <td>{{ $quiz->subject_name }}</td>
                                <td>{{ $quiz->faculty_name }}</td>
                                <td>{{ $quiz->date }}</td>
                                <td>{{ $quiz->start_time }}</td>
                                <td>{{ $quiz->end_time }}</td>
                                <td>
                                    @if ($attempt && $attempt->status == 1)
                                        <!-- Test already submitted -->
                                        <a href="{{ route('attempts.results', ['quizId' => $quiz->id]) }}"
                                            class="btn btn-primary btn-sm">View Result and Response</a>
                                    @elseif ($currentDateTime->lt($quizStartDateTime))
                                        <!-- Timer for quiz start -->
                                        <span id="timer-{{ $quiz->id }}" class="text-danger fw-bold"></span>
                                        <button id="start-btn-{{ $quiz->id }}" class="btn btn-primary btn-sm"
                                            disabled>Start Quiz</button>
                                        <script>
                                            const startTime{{ $quiz->id }} = new Date("{{ $quizStartDateTime }}").getTime();
                                            const timer{{ $quiz->id }} = setInterval(() => {
                                                const now = new Date().getTime();
                                                const distance = startTime{{ $quiz->id }} - now;

                                                if (distance <= 0) {
                                                    clearInterval(timer{{ $quiz->id }});
                                                    document.getElementById("timer-{{ $quiz->id }}").innerText = "Quiz is live!";
                                                    const startBtn = document.getElementById("start-btn-{{ $quiz->id }}");
                                                    startBtn.removeAttribute('disabled');
                                                    startBtn.onclick = function() {
                                                        window.location.href = "{{ route('attempts.create', $quiz->id) }}";
                                                    };
                                                } else {
                                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                    document.getElementById("timer-{{ $quiz->id }}").innerText =
                                                        `Starts in: ${hours}h ${minutes}m ${seconds}s`;
                                                }
                                            }, 1000);
                                        </script>
                                    @elseif ($currentDateTime->gt($quizEndDateTime) || ($attempt && $attempt->status == 1))
                                        <!-- Time is over or quiz already submitted -->
                                        <a href="{{ route('attempts.responses', ['attemptId' => $attempt->id ?? null]) }}"
                                            class="btn btn-primary btn-sm">View Result and Response</a>
                                    @else
                                        @if (!$attempt || $attempt->status == 0)
                                            <!-- Test can be started -->
                                            <a href="{{ route('attempts.create', $quiz->id) }}"
                                                class="btn btn-primary btn-sm">Start Quiz</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
