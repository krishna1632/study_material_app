@extends('layouts.admin')

@section('title', 'Edit Subject')

@section('content')
    <h1 class="mt-4">Edit Subject</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
        <li class="breadcrumb-item active">Edit Subject</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Subject
        </div>
        <div class="card-body">
            <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('POST') <!-- Corrected method -->

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="subject_type" class="form-label">Subject Type</label>
                        <select name="subject_type" id="subject_type" class="form-select" required onchange="toggleDepartmentOptions()">
                            <option value="CORE" {{ $subject->subject_type == 'CORE' ? 'selected' : '' }}>CORE</option>
                            <option value="SEC" {{ $subject->subject_type == 'SEC' ? 'selected' : '' }}>SEC</option>
                            <option value="VAC" {{ $subject->subject_type == 'VAC' ? 'selected' : '' }}>VAC</option>
                            <option value="AEC" {{ $subject->subject_type == 'AEC' ? 'selected' : '' }}>AEC</option>
                            <option value="GE" {{ $subject->subject_type == 'GE' ? 'selected' : '' }}>GE</option>
                            <option value="DSE" {{ $subject->subject_type == 'DSE' ? 'selected' : '' }}>DSE</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <select name="department" id="department" class="form-select" required>
                            <option value="" disabled>Select Department</option>
                            <option value="Applied Psychology" {{ $subject->department == 'Applied Psychology' ? 'selected' : '' }}>Department of Applied Psychology</option>
                            <option value="Computer Science" {{ $subject->department == 'Computer Science' ? 'selected' : '' }}>Department of Computer Science</option>
                            <option value="B.voc(Software Development)" {{ $subject->department == 'B.voc(Software Development)' ? 'selected' : '' }}>Department of B.voc (Software Development)</option>
                            <option value="Economics" {{ $subject->department == 'Economics' ? 'selected' : '' }}>Department of Economics</option>
                            <option value="English" {{ $subject->department == 'English' ? 'selected' : '' }}>Department of English</option>
                            <option value="Environmental Studies" {{ $subject->department == 'Environmental Studies' ? 'selected' : '' }}>Department of Environmental Studies</option>
                            <option value="Commerce" {{ $subject->department == 'Commerce' ? 'selected' : '' }}>Department of Commerce</option>
                            <option value="Punjabi" {{ $subject->department == 'Punjabi' ? 'selected' : '' }}>Department of Punjabi</option>
                            <option value="ELECTIVE" {{ $subject->department == 'ELECTIVE' ? 'selected' : '' }}>ELECTIVE</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select" required>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ $subject->semester == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" name="subject_name" id="subject_name" class="form-control" value="{{ $subject->subject_name }}" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDepartmentOptions() {
            const subjectType = document.getElementById('subject_type').value;
            const departmentField = document.getElementById('department');
            const options = departmentField.options;

            try {
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];

                    if (subjectType === 'CORE' || subjectType === 'DSE') {
                        option.style.display = option.value === 'ELECTIVE' ? 'none' : 'block';
                    } else if (['GE', 'SEC', 'VAC', 'AEC'].includes(subjectType)) {
                        option.style.display = option.value === 'ELECTIVE' ? 'block' : 'none';
                    } else {
                        option.style.display = 'block';
                    }
                }
            } catch (error) {
                console.error("Error updating department options:", error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleDepartmentOptions();
        });
    </script>

    @if (session('success'))
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
        </script>
    @endif

    @if (session('error'))
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    showConfirmButton: true,
                });
            }
        </script>
    @endif
@endsection
