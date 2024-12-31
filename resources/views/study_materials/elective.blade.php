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
                <div class="mb-3">
                    <button type="submit" id="fetch-materials" class="btn btn-primary" disabled>Fetch Study
                        Materials</button>
                </div>
            </form>
        </div>
    </div>

    <div id="study-materials-container" class="mt-4">
        <!-- Study materials will be displayed here -->
    </div>

    <script>
        document.getElementById('subject_type').addEventListener('change', function() {
            const subjectType = this.value;
            const semester = @json(auth()->user()->semester);

            const subjectNameDropdown = document.getElementById('subject_name');
            subjectNameDropdown.innerHTML = '<option value="" disabled selected>Fetching subjects...</option>';
            subjectNameDropdown.disabled = true;

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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch subjects');
                    }
                    return response.json();
                })
                .then(subjects => {
                    subjectNameDropdown.innerHTML =
                        '<option value="" disabled selected>Select Subject Name</option>';
                    for (const [id, name] of Object.entries(subjects)) {
                        subjectNameDropdown.innerHTML += `<option value="${id}">${name}</option>`;
                    }
                    subjectNameDropdown.disabled = false;
                })
                .catch(error => {
                    console.error(error);
                    subjectNameDropdown.innerHTML = '<option value="" disabled>No subjects found</option>';
                });
        });

        // Enable button on subject_name change
        document.getElementById('subject_name').addEventListener('change', function() {
            const fetchMaterialsButton = document.getElementById('fetch-materials');
            fetchMaterialsButton.disabled = !this.value; // Enable only if a value is selected
        });

        document.getElementById('fetch-materials').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            const subjectId = document.getElementById('subject_name').value;
            const materialsContainer = document.getElementById('study-materials-container');
            materialsContainer.innerHTML = '<p>Fetching study materials...</p>';

            fetch(`/fetch-study-materials/${subjectId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch study materials');
                    }
                    return response.json();
                })
                .then(materials => {
                    if (materials.length > 0) {
                        materialsContainer.innerHTML = materials.map(material => `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">${material.subject_name}</h5>
                                    <p class="card-text">${material.description || 'No description available'}</p>
                                    <a href="/storage/${material.file}" class="btn btn-primary" target="_blank">Download</a>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        materialsContainer.innerHTML = '<p>No study materials available for this subject.</p>';
                    }
                })
                .catch(error => {
                    console.error(error);
                    materialsContainer.innerHTML = '<p>Error fetching study materials.</p>';
                });
        });
    </script>
@endsection
