@extends('layouts.admin')

@section('title', 'Quiz Results')

@section('content')
    <h1 class="mt-4">Quiz Results</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Quiz Results</li>
    </ol>

    <!-- Export Buttons -->
    <div class="mb-4">
        <a href="#" class="btn btn-success">Export to Excel</a>
        <a href="#" class="btn btn-primary">Export to Word</a>
        <a href="#" class="btn btn-danger">Export to PDF</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Semester</th>
                        <th>Department</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentsResults as $result)
                        <tr>
                            <td>{{ $result['name'] }}</td>
                            <td>{{ $result['roll_no'] }}</td>
                            <td>{{ $result['semester'] }}</td>
                            <td>{{ $result['department'] }}</td>
                            <td>{{ $result['marks'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>


    </div>
    <div class="text-start">
        <a href="{{ route('quiz_reports.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection
