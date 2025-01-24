@extends('layouts.admin')

@section('title', 'Elective Study Materials')

@section('content')
    <h1 class="mt-4">Elective Study Materials</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Elective Study Materials</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-book me-1"></i>
            Filter Elective Study Materials

            <a href="{{ route('study_materials.index') }}" class="btn btn-secondary float-end">Cancel</a>
        </div>


        <div class="card-body">
            <form id="filter-form">
                <div class="mb-3">
                    <label for="subject_type" class="form-label">Subject Type</label>
                    <select id="subject_type" class="form-select">
                        <option value="" disabled selected>Select Subject Type</option>
                        <option value="SEC">SEC</option>
                        <option value="VAC">VAC</option>
                        <option value="GE">GE</option>
                        <option value="AEC">AEC</option>
                    </select>
                </div>



                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject Name</label>
                    <select id="subject_name" class="form-select" disabled>
                        <option value="" disabled selected>Select Subject Name</option>
                    </select>
                </div>

            </form>
        </div>

    </div>

    <div id="study-materials" class="mt-4">
        <!-- Study materials will be dynamically loaded here -->
    </div>

    <!-- Spinner for loading -->
    <div id="loading-spinner" class="d-none text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
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

            const studyMaterialsContainer = document.getElementById('study-materials');
            studyMaterialsContainer.innerHTML = ''; // Clear previous results

            // Show loading spinner
            document.getElementById('loading-spinner').classList.remove('d-none');

            // Using AJAX to fetch study materials
            $.ajax({
                url: '/filter-study',
                method: 'POST',
                data: {
                    subject_type: subjectType,
                    subject_name: subjectName, // Fixed key
                    semester: semester,
                    department: department,
                    _token: '{{ csrf_token() }}', // Include CSRF token
                },
                success: function(studyMaterials) {
                    document.getElementById('loading-spinner').classList.add('d-none');
                    if (studyMaterials.data && studyMaterials.data.length > 0) {
                        let htmlContent = '<h3>Study Materials</h3>';
                        htmlContent += '<ul class="list-group">';
                        for (let i = 0; i < studyMaterials.data.length; i++) {
                            const material = studyMaterials.data[i];

                            // Check if the subject name matches the selected subject
                            if (material.subject_name === selectedSubjectName) {
                                htmlContent += `
                        <li class="list-group-item">
                            <p><strong>Subject Name:</strong> ${material.subject_name}</p>
                            <p><strong>Faculty Name:</strong> ${material.faculty_name}</p>
                            <p><strong>Description:</strong> ${material.description || 'No description available'}</p>
                            <a href="/storage/${material.file}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                        </li>`;
                            }
                        }
                        htmlContent += '</ul>';
                        studyMaterialsContainer.innerHTML = htmlContent;

                        // If no materials are found, display a message
                        if (htmlContent === '<h3>Study Materials</h3><ul class="list-group"></ul>') {
                            studyMaterialsContainer.innerHTML =
                                '<p class="text-danger">No study materials found for the selected subject.</p>';
                        }
                    } else {
                        studyMaterialsContainer.innerHTML =
                            '<p class="text-danger">No study materials found for the selected subject.</p>';
                    }
                },
                error: function(error) {
                    console.error(error);
                    document.getElementById('loading-spinner').classList.add('d-none');
                    studyMaterialsContainer.innerHTML =
                        '<p class="text-danger">No study materials found for the provided filters.</p>';
                }
            });
        });
    </script>



@endsection
