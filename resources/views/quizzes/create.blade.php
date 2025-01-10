@extends('layouts.admin')

@section('title', 'Create Quiz')

@section('content')
    <h1 class="mt-4">Create Quiz</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Quizzes</a></li>
        <li class="breadcrumb-item active">Create Quiz</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Add New Quiz
        </div>
        <div class="card-body">
            <form id="quizForm" action="{{ route('quizzes.store') }}" method="POST">
                @csrf
                <!-- Subject Type Field -->
                <div class="mb-3">
                    <label for="subject_type" class="form-label">Subject Type<font color="red">*</font></label>
                    <select name="subject_type" id="subject_type" class="form-select" required>
                        <option value="" disabled selected>Select Subject Type</option>
                        <option value="CORE">CORE</option>
                        <option value="SEC">SEC</option>
                        <option value="VAC">VAC</option>
                        <option value="AEC">AEC</option>
                        <option value="GE">GE</option>
                        <option value="DSE">DSE</option>
                    </select>
                </div>

                <!-- Department Field -->
                <div class="mb-3">
                    <label for="department" class="form-label">Department/Elective<font color="red">*</font></label>
                    <select name="department" id="department" class="form-select" required>
                        <option value="" disabled selected>Select Department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester Field -->
                <div class="mb-3">
                    <label for="semester" class="form-label">Semester<font color="red">*</font></label>
                    <select name="semester" id="semester" class="form-select" required>
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
                </div>

                <!-- Subject Name Field -->
                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject<font color="red">*</font></label>
                    <select name="subject_name" id="subject_name" class="form-select" required>
                        <option value="" disabled selected>Select Subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Faculty Name Field -->
                <div class="mb-3">
                    <label for="faculty_name" class="form-label">Faculty Name<font color="red">*</font></label>
                    <select name="faculty_name" id="faculty_name" class="form-select" required>
                        <option value="" disabled selected>Select Faculty Name</option>
                        @if ($roles->contains('Admin') || $roles->contains('SuperAdmin'))
                            <option value="Admin" class="admin-option">Admin</option>
                        @endif
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->name }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Quiz Date Field -->
                <div class="mb-3">
                    <label for="date" class="form-label">Quiz Date<font color="red">*</font></label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>

                <!-- Quiz Time Field -->
                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" id="start_time" required>
                </div>

                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" id="end_time" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create Your Questions</button>
                    <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Filter department dropdown based on subject type
        document.getElementById('subject_type').addEventListener('change', function() {
            const subjectType = this.value;
            const departmentField = document.getElementById('department');
            departmentField.innerHTML = '<option value="" disabled selected>Select Department</option>';

            if (subjectType === 'CORE' || subjectType === 'DSE') {
                @json($departments).forEach(department => {
                    if (department !== 'ELECTIVE') {
                        departmentField.innerHTML += `<option value="${department}">${department}</option>`;
                    }
                });
            } else if (['SEC', 'VAC', 'GE', 'AEC'].includes(subjectType)) {
                departmentField.innerHTML += `<option value="ELECTIVE">ELECTIVE</option>`;
            }
        });

        // Filter subjects based on subject type, department, and semester
        document.getElementById('subject_type').addEventListener('change', filterSubjects);
        document.getElementById('department').addEventListener('change', filterSubjects);
        document.getElementById('semester').addEventListener('change', filterSubjects);

        function filterSubjects() {
            const subjectType = document.getElementById('subject_type').value;
            const department = document.getElementById('department').value;
            const semester = document.getElementById('semester').value;
            const subjectField = document.getElementById('subject_name');

            subjectField.innerHTML = '<option value="" disabled selected>Select Subject</option>';

            if (subjectType && department && semester) {
                console.log("Sending data to server:", {
                    subjectType,
                    department,
                    semester
                }); // Debug

                fetch("/filter-subjects", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            subject_type: subjectType,
                            department: department,
                            semester: semester
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Response from server:", data); // Debug

                        if (data.success) {
                            data.data.forEach(subject => {
                                subjectField.innerHTML +=
                                    `<option value="${subject.subject_name}">${subject.subject_name}</option>`;
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Subjects Found',
                                text: data.message || 'No subjects are available for the selected criteria.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error in fetching subjects:", error); // Debug
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong while fetching subjects.'
                        });
                    });
            }
        }

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
