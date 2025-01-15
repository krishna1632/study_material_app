@extends('layouts.admin')

@section('title', 'View Quiz Details')

@section('content')
    <h1 class="mt-4">Start Quiz</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attempts.index') }}">Attempt Quiz</a></li>
        <li class="breadcrumb-item active">Start Quiz</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-question-circle me-1"></i>
            Student Details
        </div>
        <div class="card-body">
            <form action="{{ route('attempts.store') }}" method="POST">
                @csrf

                <!-- Hidden Quiz ID -->
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div class="mb-3">
                    <label for="student_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name"
                        value="{{ $user->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" name="department"
                        value="{{ $user->department }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="text" class="form-control" id="semester" name="semester" value="{{ $user->semester }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="subject_type" class="form-label">Subject Type</label>
                    <input type="text" class="form-control" id="subject_type" name="subject_type"
                        value="{{ $quiz->subject_type }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject Name</label>
                    <input type="text" class="form-control" id="subject_name" name="subject_name"
                        value="{{ $quiz->subject_name }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="roll_number" class="form-label">Roll Number</label>
                    <input type="text" class="form-control" id="roll_number" name="roll_no" required>
                </div>

                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
@endsection
