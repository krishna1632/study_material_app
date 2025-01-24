@extends('layouts.admin')

@section('title', 'Start Quiz')

@section('content')
    <h1 class="mt-4 text-center">Start Quiz: {{ $quiz->subject_name }}</h1>
    <ol class="breadcrumb mb-4 justify-content-center"></ol>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <!-- Timer Section -->
            <div class="mb-4 text-center">
                <h2 class="fw-bold text-danger">Time Left: <span id="timer"></span></h2>
            </div>

            <!-- Questions container -->
            <form id="quizForm" action="{{ route('attempts.submitTest', $quiz->id) }}" method="POST">
                @csrf
                <div id="questionsContainer">
                    @foreach ($questions as $question)
                        <div class="mb-5 p-3 border rounded shadow-sm bg-light">
                            <p class="fw-bold text-dark">
                                <span class="text-primary">Q{{ $loop->iteration }}:</span>
                                {{ $question->question_text }}
                            </p>
                            <div class="mt-3">
                                @foreach (json_decode($question->options) as $option)
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="answers[{{ $question->id }}]"
                                            value="{{ $option }}"
                                            id="option{{ $loop->parent->iteration }}{{ $loop->iteration }}">
                                        <label for="option{{ $loop->parent->iteration }}{{ $loop->iteration }}"
                                            class="form-check-label text-dark">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Submit Test</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check if the backend response contains an alert flag
        @if (session('alert'))
            Swal.fire({
                title: 'Test Already Submitted!',
                text: "{{ session('message') }}",
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ session('redirect') }}";
            });
        @endif

        let formSubmitted = false;
        // Function to detect illegal activity (minimize, tab change, etc.)
        function detectIllegalActivity() {
            if (formSubmitted) return;

            // Detect window blur (e.g., clicking minimize or switching tabs)
            window.addEventListener('blur', function() {
                submitTestDueToActivity();
            });

            // Detect visibility change (e.g., switching tabs)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    submitTestDueToActivity();
                }
            });
        }

        // Function to handle submission due to illegal activity
        function submitTestDueToActivity() {
            if (formSubmitted) return;
            formSubmitted = true;

            // Show SweetAlert confirmation before submitting the test
            Swal.fire({
                title: "Illegal Activity Detected!",
                text: "Your test is being submitted due to this activity.",
                icon: "warning",
                confirmButtonText: "OK"
            }).then(() => {
                // Submit the form immediately after alert
                document.getElementById('quizForm').submit();
            });
        }

        // Start detecting activity
        detectIllegalActivity();

        // Timer logic
        const endTime = new Date('{{ \Carbon\Carbon::parse($quiz->end_time)->toDateTimeString() }}')
            .getTime(); // Quiz end time from backend
        const timerElement = document.getElementById('timer');
        const form = document.getElementById('quizForm');

        function updateTimer() {
            const now = new Date().getTime();
            const timeLeft = endTime - now;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                form.submit(); // Auto-submit the form if time is up
                return;
            }

            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            timerElement.textContent = `${minutes}m ${seconds}s`;
        }

        const timerInterval = setInterval(updateTimer, 1000); // Update every second
        updateTimer(); // Initial call
    </script>


@endsection
