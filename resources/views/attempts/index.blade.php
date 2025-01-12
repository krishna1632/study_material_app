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
                {{-- <tr> --}}
                    <th>ID</th>
                    <td>{{ $quiz->id }}</td>
                </tr>
                <tr>
                    <th>Subject Type</th>
                    <td>{{ $quiz->subject_type }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $quiz->department }}</td>
                </tr>
                <tr>
                    <th>Semester</th>
                    <td>{{ $quiz->semester }}</td>
                </tr>
                <tr>
                    <th>Subject Name</th>
                    <td>{{ $quiz->subject_name }}</td>
                </tr>
                <tr>
                    <th>Faculty Name</th>
                    <td>{{ $quiz->faculty_name }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $quiz->date }}</td>
                </tr>
                <tr>
                    <th>Start Time</th>
                    <td>{{ \Carbon\Carbon::parse($quiz->start_time)->format('h:i A') }}</td>
                </tr>
                <tr>
                    <th>End Time</th>
                    <td>{{ \Carbon\Carbon::parse($quiz->end_time)->format('h:i A') }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection