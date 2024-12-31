@extends('layouts.admin')

@section('title', 'Edit Study Material')

@section('content')
    <h1 class="mt-4">Edit Study Material</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('study_materials.index') }}">Study Materials</a></li>
        <li class="breadcrumb-item active">Edit Study Material</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Study Material
        </div>
        <div class="card-body">
            <form action="{{ route('study_materials.update', $studyMaterial->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Subject Type Field -->
                <div class="mb-3">
                    <label for="subject_type" class="form-label">Subject Type<font color="red">*</font></label>
                    <select name="subject_type" id="subject_type" class="form-select" required>
                        <option value="CORE" {{ $studyMaterial->subject_type === 'CORE' ? 'selected' : '' }}>CORE</option>
                        <option value="SEC" {{ $studyMaterial->subject_type === 'SEC' ? 'selected' : '' }}>SEC</option>
                        <option value="VAC" {{ $studyMaterial->subject_type === 'VAC' ? 'selected' : '' }}>VAC</option>
                        <option value="AEC" {{ $studyMaterial->subject_type === 'AEC' ? 'selected' : '' }}>AEC</option>
                        <option value="GE" {{ $studyMaterial->subject_type === 'GE' ? 'selected' : '' }}>GE</option>
                        <option value="DSE" {{ $studyMaterial->subject_type === 'DSE' ? 'selected' : '' }}>DSE</option>
                    </select>
                </div>

                <!-- Department Field -->
                <div class="mb-3">
                    <label for="department" class="form-label">Department/Elective</label>
                    <select name="department" id="department" class="form-control">
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}"
                                {{ $studyMaterial->department === $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester Field -->
                <div class="mb-3">
                    <label for="semester" class="form-label">Semester<font color="red">*</font></label>
                    <select name="semester" id="semester" class="form-select" required>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ $studyMaterial->semester == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Subject Name Field -->
                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject<font color="red">*</font></label>
                    <select name="subject_name" id="subject_name" class="form-control" required>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject }}"
                                {{ $studyMaterial->subject_name === $subject ? 'selected' : '' }}>
                                {{ $subject }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Faculty Name Field -->
                <div class="mb-3">
                    <label for="faculty_name" class="form-label">Faculty Name<font color="red">*</font></label>
                    <select name="faculty_name" id="faculty_name" class="form-control" required>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->name }}"
                                {{ $studyMaterial->faculty_name === $faculty->name ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- File Upload Field -->
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File (Optional)</label>
                    <input type="file" name="file" id="file" class="form-control">
                    <small>Current File: {{ $studyMaterial->file }}</small>
                </div>

                <!-- Description Field -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control" required>{{ $studyMaterial->description }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="text-end">
                    <a href="{{ route('study_materials.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
