@extends('layouts.admin')

@section('title', 'Start Quiz')

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
            <div class="alert alert-danger">
                <strong>Note:</strong> Fill your details carefully. Once you submit your details, they will be saved and you
                cannot change them.
            </div>

            @error('already_submitted')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <form id="quizForm" action="{{ route('attempts.store') }}" method="POST">
                @csrf
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
                    <input type="text" class="form-control" id="roll_number" name="roll_no" required
                        value="{{ $user->roll_no }}" readonly>
                </div>

                @if ($existingAttempt)
                    <button type="button" class="btn btn-primary" id="viewAttemptsButton"
                        onclick="window.location.href='{{ route('attempts.show', ['id' => $existingAttempt->id]) }}'">
                        Move to Instructions
                    </button>
                @else
                    <button type="submit" class="btn btn-success" id="submitButton">Submit</button>
                @endif
            </form>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('submitButton')?.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Please check your all details before submitting.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('quizForm').submit();
                }
            });
        });

        @if (session('alert'))
            Swal.fire({
                title: 'Already Submitted!',
                text: "{{ session('message') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('attempts.show', ['id' => session('attempt_id')]) }}";
            });
        @endif

        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    });
</script>
