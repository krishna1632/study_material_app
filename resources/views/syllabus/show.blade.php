@extends('layouts.admin')

@section('title', 'View Syllabus')

@section('content')
    <div class="page-wrapper" style="margin-top: 3rem;">
        <div class="page-content">
            <div class="card p-4">
                <div class="card-body">
                    <h3 class="h2 mb-4">Syllabus Details</h3>

                    <!-- Subject Type -->
                    <div class="mb-3">
                        <strong>Subject Type:</strong>
                        <p>{{ $syllabus->subject_type }}</p>
                    </div>

                    <!-- Subject Name -->
                    <div class="mb-3">
                        <strong>Subject Name:</strong>
                        <p>{{ $syllabus->subject_name }}</p>
                    </div>

                    <!-- File -->
                    <div class="mb-3">
                        <strong>File:</strong>
                        @if ($syllabus->file)
                            <p>
                                <a href="{{ asset('storage/' . $syllabus->file) }}" target="_blank" class="btn btn-primary btn-sm">
                                    View File
                                </a>
                            </p>
                        @else
                            <p>No file available.</p>
                        @endif
                    </div>

                    <a href="{{ route('syllabus.index') }}" class="btn btn-secondary">Back to Syllabus List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
