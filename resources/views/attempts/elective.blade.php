@extends('layouts.admin')

@section('title', 'Elective Test')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Elective Study Materials</h1>
        <ol class="breadcrumb bg-light p-3 rounded">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-primary">Dashboard</a></li>
            <li class="breadcrumb-item active">Elective Test</li>
        </ol>

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Elective Test</h5>
                <a href="{{ route('attempts.index') }}" class="btn btn-light btn-sm">Back</a>
            </div>
            <div class="card-body">
                <form id="filter-form" class="row g-3">
                    <!-- Subject Type -->
                    <div class="col-md-6">
                        <label for="subject_type" class="form-label fw-bold">Subject Type</label>
                        <select id="subject_type" class="form-select">
                            <option value="" disabled selected>Select Subject Type</option>
                            <option value="SEC">SEC</option>
                            <option value="VAC">VAC</option>
                            <option value="GE">GE</option>
                            <option value="AEC">AEC</option>
                        </select>
                    </div>

                    <!-- Subject Name -->
                    <div class="col-md-6">
                        <label for="subject_name" class="form-label fw-bold">Subject Name</label>
                        <select id="subject_name" class="form-select" disabled>
                            <option value="" disabled selected>Select Subject Name</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Spinner for loading -->
        <div id="loading-spinner" class="text-center d-none mb-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Elective List -->
        <div id="elective-test-container" class="row g-3">
            <!-- Elective will be dynamically loaded here -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('subject_type').addEventListener('change', function() {
            const subjectType = this.value;
            const semester = @json(auth()->user()->semester);

            const subjectNameDropdown = document.getElementById('subject_name');
            subjectNameDropdown.innerHTML = '<option value="" disabled selected>Fetching subjects...</option>';
            subjectNameDropdown.disabled = true;

            // Using AJAX to fetch subjects
            $.ajax({
                url: '/filter-subjects',
                method: 'POST',
                data: {
                    subject_type: subjectType,
                    semester: semester,
                    department: 'ELECTIVE',
                    _token: '{{ csrf_token() }}', // Include CSRF token
                },
                success: function(subjects) {
                    subjectNameDropdown.innerHTML =
                        '<option value="" disabled selected>Select Subject Name</option>';
                    for (const [id, name] of Object.entries(subjects)) {
                        subjectNameDropdown.innerHTML += `<option value="${id}">${name}</option>`;
                    }
                    subjectNameDropdown.disabled = false;
                },
                error: function(error) {
                    console.error(error);
                    subjectNameDropdown.innerHTML =
                        '<option value="" disabled>No subjects found</option>';
                }
            });
        });

        document.getElementById('subject_name').addEventListener('change', function() {
            const selectedSubjectName = this.options[this.selectedIndex].text; // Get selected subject name
            const subjectName = this.value; // Correct variable for subject ID
            const subjectType = document.getElementById('subject_type').value;
            const semester = @json(auth()->user()->semester);
            const department = 'ELECTIVE';

            const quizContainer = document.getElementById('elective-test-container');
            quizContainer.innerHTML = ''; // Clear previous results

            // Show loading spinner
            document.getElementById('loading-spinner').classList.remove('d-none');

            // Using AJAX to fetch study materials
            $.ajax({
                url: '/filter-quiz',
                method: 'POST',
                data: {
                    subject_type: subjectType,
                    subject_name: subjectName, // Fixed key
                    semester: semester,
                    department: department,
                    _token: '{{ csrf_token() }}', // Include CSRF token
                },
                success: function(response) {
                    document.getElementById('loading-spinner').classList.add('d-none');

                    if (response.data && response.data.length > 0) {
                        let htmlContent = `
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
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
                        `;

                        response.data.forEach((quiz, index) => {
                            htmlContent += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${quiz.subject_type}</td>
                                <td>${quiz.department}</td>
                                <td>${quiz.semester}</td>
                                <td>${quiz.subject_name}</td>
                                <td>${quiz.faculty_name}</td>
                                <td>${quiz.date}</td>
                                <td>${quiz.start_time}</td>
                                <td>${quiz.end_time}</td>
                                <td>
                                    <a href="{{ route('attempts.create', '') }}/${quiz.id}" class="btn btn-primary btn-sm">Start Quiz</a>
                                </td>
                            </tr>
                            `;
                        });

                        htmlContent += `
                            </tbody>
                        </table>`;
                        quizContainer.innerHTML = htmlContent;
                    } else {
                        quizContainer.innerHTML =
                            '<p class="text-danger">No quizzes found for the provided filters.</p>';
                    }
                },
                error: function(error) {
                    console.error(error);
                    document.getElementById('loading-spinner').classList.add('d-none');
                    quizContainer.innerHTML =
                        '<p class="text-danger">Error fetching quizzes. Please try again.</p>';
                },
            });
        });
    </script>



@endsection
