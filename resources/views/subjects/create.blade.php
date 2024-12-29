@extends('layouts.admin')

@section('title', 'Create Subject')

@section('content')
    <h1 class="mt-4">Create Subject</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
        <li class="breadcrumb-item active">Create Subject</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Add New Subject
        </div>
        <div class="card-body">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="subject_type" class="form-label">Subject Type</label>
                        <select name="subject_type" id="subject_type" class="form-select" required onchange="toggleDepartmentField()">
                            <option value="" disabled selected>Select Type</option>
                            <option value="CORE">Core</option>
                            <option value="SEC">SEC</option>
                            <option value="VAC">VAC</option>
                            <option value="AEC">AEC</option>
                            <option value="GE">GE</option>
                            <option value="DSE">DSE</option>
                        </select>
                    </div>
                    <div class="col-md-6">
    <label for="department" class="form-label">Department</label>
    <select name="department" id="department" class="form-select" required>
        <option value="" disabled selected>Select Department</option>
        <option value="Applied Psychology">Department of Applied Psychology</option>
        <option value="Computer Science">Department of Computer Science</option>
        <option value="B.voc(Software Development)">B.voc (Software Development)</option>
        <option value="Economics">Department of Economics</option>
        <option value="English">Department of English</option>
        <option value="Environmental Studies">Department of Environmental Studies</option>
        <option value="Commerce">Department of Commerce</option>
        <option value="Punjabi">Department of Punjabi</option>
        <option value="Hindi">Department of Hindi</option>
        <option value="History">Department of History</option>
        <option value="Management Studies">Department of Management Studies</option>
        <option value="Mathematics">Department of Mathematics</option>
        <option value="Philosophy">Department of Philosophy</option>
        <option value="Physical Education">Department of Physical Education</option>
        <option value="Political Science">Department of Political Science</option>
        <option value="Statistics">Department of Statistics</option>
        <option value="B.voc(Banking Operations)">B.voc(Banking Operations)</option>
    </select>
    @error('department')
        <div class="text-danger mt-2">{{ $message }}</div>
    @enderror
</div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select">
                            <option value="" disabled selected>Select Semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                        @error('semester')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" name="subject_name" id="subject_name" class="form-control" placeholder="Enter Subject Name" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create Subject</button>
                    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDepartmentField() {
            const subjectType = document.getElementById('subject_type').value;
            const departmentField = document.getElementById('department');

            if (subjectType === 'CORE' || subjectType === 'DSE') {
                departmentField.disabled = false;
                departmentField.required = true;
            } else {
                departmentField.disabled = true;
                departmentField.required = false;
                departmentField.value = ''; // Reset the value if disabled
            }
        }

        // Call this function on page load to set the initial state of the department field
        window.onload = toggleDepartmentField;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Success Popup -->
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

    <!-- SweetAlert Error Popup -->
    @if (session('error'))
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Entry',
                    text: "{{ session('error') }}",
                    showConfirmButton: true,
                });
            }
        </script>
    @endif
@endsection
