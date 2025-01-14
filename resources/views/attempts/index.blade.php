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
                                    <a href="{{ route('attempts.create', $quiz->id) }}" class="btn btn-primary btn-sm">Start
                                        Quiz</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
