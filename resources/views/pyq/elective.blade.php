@extends('layouts.admin')

@section('title', 'Elective Pyq')

@section('content')
    <h1 class="mt-4">Elective PYQ</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Elective PYQ</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-book me-1"></i>
            Filter Elective Pyq
            <a href="{{ route('pyq.index') }}" class="btn btn-secondary float-end">Back</a>
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
                  <!-- Year -->
                  <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-control" required>
                        <option value="" disabled selected>Select Year</option>
                        @for ($i = 2015; $i <= 2025; $i++)
                            <option value="{{ $i }}"> {{ $i }}</option>
                        @endfor
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

   

    <!-- Spinner for loading -->
    <div id="loading-spinner" class="d-none text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div id="pyq-container"></div>

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


document.getElementById('subject_name').addEventListener('change', function () {
    const selectedSubjectName = this.options[this.selectedIndex].text; // Get selected subject name
    const subjectName = this.value; // Correct variable for subject ID
    const subjectType = document.getElementById('subject_type').value;
    const semester = @json(auth()->user()->semester);
    const department = 'ELECTIVE';
    const year = document.getElementById('year').value; // Get the selected year

    const pyqContainer = document.getElementById('pyq-container');
    pyqContainer.innerHTML = ''; // Clear previous results

    // Show loading spinner
    document.getElementById('loading-spinner').classList.remove('d-none');

    // Using AJAX to fetch PYQs
    $.ajax({
        url: '/filter-pyqs',
        method: 'POST',
        data: {
            subject_type: subjectType,
            subject_name: subjectName,
            semester: semester,
            department: department,
            year: year, // Include year filter
            _token: '{{ csrf_token() }}', // Include CSRF token
        },
        success: function (pyqs) {
            document.getElementById('loading-spinner').classList.add('d-none');
            if (pyqs.data && pyqs.data.length > 0) {
                let htmlContent = '<h3>Previous Year Questions</h3>';
                htmlContent += '<ul class="list-group">';
                for (let i = 0; i < pyqs.data.length; i++) {
                    const pyq = pyqs.data[i];
                    
                    // Check if the subject name matches the selected subject
                    if (pyq.subject_name === selectedSubjectName) {
                        htmlContent += `
                        <li class="list-group-item">
                            <p><strong>Subject Name:</strong> ${pyq.subject_name}</p>
                            <p><strong>Year:</strong> ${pyq.year}</p>
                            <p><strong>Faculty Name:</strong> ${pyq.faculty_name}</p>
                          
                            <a href="/storage/${pyq.file}" target="_blank" class="btn btn-primary btn-sm">View Pyq file</a>
                        </li>`;
                    }
                }
                htmlContent += '</ul>';
                pyqContainer.innerHTML = htmlContent;

                // If no PYQs are found, display a message
                if (htmlContent === '<h3>Previous Year Questions</h3><ul class="list-group"></ul>') {
                    pyqContainer.innerHTML = '<p class="text-danger">No PYQs found for the selected filters.</p>';
                }
            } else {
                pyqContainer.innerHTML = '<p class="text-danger">No PYQs found for the selected filters.</p>';
            }
        },
        error: function (error) {
            console.error(error);
            document.getElementById('loading-spinner').classList.add('d-none');
            pyqContainer.innerHTML = '<p class="text-danger">An error occurred while fetching PYQs.</p>';
        }
    });
});
 
    </script>
@endsection
