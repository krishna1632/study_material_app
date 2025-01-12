@extends('layouts.admin')

@section('title', 'Write Instructions')

@section('content')
    <h1 class="mt-4">Write Instructions for the Quiz</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Quizzes</a></li>
        <li class="breadcrumb-item active">Write Instructions</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-pencil-alt me-1"></i>
            Instructions Form
        </div>
        <div class="card-body">
            <form action="{{ route('quizzes.update.instructions', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="instructions" class="form-label">Instructions</label>
                    <textarea id="instructions" name="instructions" class="form-control" rows="5">{{ old('instructions', $quiz->instructions) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject Name</label>
                    <input type="text" id="subject_name" name="subject_name" class="form-control"
                        value="{{ old('subject_name') }}">
                </div>

                <div class="mb-3">
                    <label for="teacher_name" class="form-label">Teacher's Name</label>
                    <input type="text" id="teacher_name" name="teacher_name" class="form-control"
                        value="{{ old('teacher_name') }}">
                </div>

                <!-- Add more fields as needed -->

                <button type="submit" class="btn btn-success">Save Instructions</button>
                <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>


@endsection
