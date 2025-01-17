@extends('layouts.admin')

@section('title', 'Start Quiz')

@section('content')
    <h1 class="mt-4 text-center">Start Quiz: {{ $quiz->subject_name }}</h1>
    <ol class="breadcrumb mb-4 justify-content-center">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Attempt Quiz</a></li>
        <li class="breadcrumb-item active">Start Test</li>
    </ol>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <h3 class="fw-bold text-primary text-center mb-4">{{ $quiz->subject_name }} - Questions</h3>

            <!-- Questions container -->
            <form action="{{ route('attempts.submitTest', $quiz->id) }}" method="POST">
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
                                        <input type="radio" 
                                               class="form-check-input" 
                                               name="answers[{{ $question->id }}]" 
                                               value="{{ $option }}" 
                                               id="option{{ $loop->parent->iteration }}{{ $loop->iteration }}" 
                                               @if (isset($previousAnswers[$question->id]) && $previousAnswers[$question->id] == $option) checked @endif 
                                               required>
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
@endsection
