@extends('layouts.admin')

@section('title', 'Start Quiz')

@section('content')
    <h1 class="mt-4">Start Quiz: {{ $quiz->subject_name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Attempt Quiz</a></li>
        <li class="breadcrumb-item active">Start Test</li>
    </ol>

    <div class="card shadow-lg border-0">
        <div class="card shadow-lg border-0 p-4">
            <div class="card-body">
                <h3 class="fw-bold text-primary">{{ $quiz->subject_name }} - Question</h3>

                <!-- Form to submit answers -->
                <form action="{{ route('attempts.submitTest', $quiz->id) }}" method="POST">
                    @csrf

                    @foreach ($questions as $question)
                        @php
                            $questionNumber =
                                ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration;
                        @endphp

                        <div class="mb-4">
                            <p><strong>Question #{{ $questionNumber }}: {{ $question->question_text }}</strong></p>

                            @foreach (json_decode($question->options) as $option)
                                <div>
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                                        @if (isset($previousAnswers[$question->id]) && $previousAnswers[$question->id] == $option) checked @endif required>
                                    <label>{{ $option }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                  
                        <div>
                            
                                <button type="submit" class="btn btn-success">Submit Test</button>
                           
                        </div>
                    
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function storeAnswers() {
            let answers = {};
            // Loop through all radio buttons and store the selected answers
            document.querySelectorAll('input[type="radio"]:checked').forEach((input) => {
                let questionId = input.name.match(/\d+/)[0]; // Extract question id
                answers[questionId] = input.value;
            });

            // Save the answers to session using AJAX
            fetch('{{ route('attempts.storeAnswers') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        answers: answers
                    })
                }).then(response => response.json())
                .then(data => {
                    console.log(data); // Handle the response if needed
                });
        }
    </script>
@endsection
