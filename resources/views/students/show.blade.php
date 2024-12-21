@extends('layouts.admin')

@section('title', 'View Student')

@section('content')
    <h1 class="mt-4">View Student</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
        <li class="breadcrumb-item active">{{ $student->name }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i>
            Student Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $student->id }}</p>
                    <p><strong>Name:</strong> {{ $student->name }}</p>
                    <p><strong>Email:</strong> {{ $student->email }}</p>
                    <p><strong>Phone:</strong> {{ $student->phone }}</p>
                    <p><strong>Department:</strong> {{ $student->department }}</p>
                    <p><strong>Created At:</strong> {{ \Carbon\Carbon::parse($student->created_at)->format('d M, Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Roles:</strong>
                        @foreach ($student->roles as $role)
                            <span class="badge bg-info">{{ $role->name }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">Back to Student List</a>
        </div>
    </div>
@endsection
