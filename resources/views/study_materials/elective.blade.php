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

    <script>
        document.getElementById('subject_type').addEventListener('change', function() {
            const subjectType = this.value;
            const semester = @json(auth()->user()->semester); // Get the logged-in student's semester

            // Clear previous subject options
            const subjectNameDropdown = document.getElementById('subject_name');
            subjectNameDropdown.innerHTML = '<option value="" disabled selected>Fetching subjects...</option>';
            subjectNameDropdown.disabled = true;

            // Fetch subjects based on the selected type and semester
            fetch(`/filter-subjects`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        subject_type: subjectType,
                        semester: semester,
                        department: 'Elective'
                    }),
                })
                .then(response => response.json())
                .then(subjects => {
                    subjectNameDropdown.innerHTML =
                        '<option value="" disabled selected>Select Subject Name</option>';
                    for (const [id, name] of Object.entries(subjects)) {
                        subjectNameDropdown.innerHTML += `<option value="${id}">${name}</option>`;
                    }
                    subjectNameDropdown.disabled = false;
                })
                .catch(error => console.error('Error fetching subjects:', error));
        });
    </script>
@endsection
