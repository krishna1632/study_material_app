@extends('layouts.admin')

@section('title', 'Edit Quiz')

@section('content')
    <h1 class="mt-4">Edit Quiz</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Quizzes</a></li>
        <li class="breadcrumb-item active">Edit Quiz</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Quiz Details
        </div>
        <div class="card-body">
            <form id="quizForm" action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- To specify it's a PUT request for update -->

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="subject_type" class="form-label">Subject Type</label>
                        <input type="text" name="subject_type" class="form-control" id="subject_type"
                            value="{{ old('subject_type', $quiz->subject_type) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" id="department"
                            value="{{ old('department', $quiz->department) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="text" name="semester" class="form-control" id="semester"
                            value="{{ old('semester', $quiz->semester) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" name="subject_name" class="form-control" id="subject_name"
                            value="{{ old('subject_name', $quiz->subject_name) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="faculty_name" class="form-label">Faculty Name</label>
                        <input type="text" name="faculty_name" class="form-control" id="faculty_name"
                            value="{{ old('faculty_name', $quiz->faculty_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" id="date"
                            value="{{ old('date', $quiz->date) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" id="start_time"
                            value="{{ old('start_time', $quiz->start_time) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" id="end_time"
                            value="{{ old('end_time', $quiz->end_time) }}" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Quiz</button>
                    <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('quizForm').addEventListener('submit', function(event) {
            const currentDate = new Date().toISOString().split('T')[0];
            const currentTime = new Date().toTimeString().split(' ')[0];

            const dateInput = document.getElementById('date').value;
            const startTimeInput = document.getElementById('start_time').value;
            const endTimeInput = document.getElementById('end_time').value;

            if (dateInput < currentDate) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'Date cannot be earlier than today!'
                });
                return;
            }

            if (dateInput === currentDate && startTimeInput < currentTime) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Start Time',
                    text: 'Start time cannot be earlier than the current time!'
                });
                return;
            }

            if (dateInput === currentDate && endTimeInput < currentTime) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid End Time',
                    text: 'End time cannot be earlier than the current time!'
                });
                return;
            }

            if (startTimeInput >= endTimeInput) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Time Range',
                    text: 'End time must be after the start time!'
                });
            }
        });
    </script>
@endsection
