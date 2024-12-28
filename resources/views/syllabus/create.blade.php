@extends('layouts.admin')

@section('title', 'Upload Syllabus')

@section('content')
    <div class="page-wrapper" style="margin-top:3rem;">
        <div class="page-content">
            <div class="card p-2">
                <div class="card-body">
                    <h3 class="h2 mb-4">Upload Syllabus</h3>
                    <form action="{{ route('syllabus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Subject Type Dropdown -->
                            <div class="col-md-6">
                                <label for="subject_type" class="form-label">Subject Type<font color="red"><b>*</b></font></label>
                                <select name="subject_type" id="subject_type" class="form-control" required>
                                    <option value="" disabled selected>Select Subject Type</option>
                                    @foreach ($subjectTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department Dropdown -->
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department<font color="red"><b>*</b></font></label>
                                <select name="department" id="department" class="form-control" required>
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester Dropdown -->
                            <div class="col-md-6 mt-3">
                                <label for="semester" class="form-label">Semester<font color="red"><b>*</b></font></label>
                                <select name="semester" id="semester" class="form-control" required>
                                    <option value="" disabled selected>Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester }}">{{ $semester }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Dropdown -->
                            <div class="col-md-6 mt-3">
                                <label for="name" class="form-label">Subject<font color="red"><b>*</b></font></label>
                                <select name="name" id="name" class="form-control" required>
                                    <option value="" disabled selected>Select Subject</option>
                                </select>
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-6 mt-3">
                                <label for="file" class="form-label">File<font color="red"><b>*</b></font></label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>

                            <!-- Form Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('syllabus.index') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
       document.getElementById('subject_type').addEventListener('change', updateSubjects);
document.getElementById('semester').addEventListener('change', updateSubjects);

function updateSubjects() {
    const subjectType = document.getElementById('subject_type').value;
    const department = document.getElementById('department').value;
    const semester = document.getElementById('semester').value;

    if (subjectType && department && semester) {
        fetchSubjects(subjectType, department, semester);
    }
}

function fetchSubjects(subjectType, department, semester) {
    const subjectDropdown = document.getElementById('name');
    subjectDropdown.innerHTML = '<option value="" disabled selected>Loading...</option>';

    fetch('{{ route('syllabus.getSubjects') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ subject_type: subjectType, department, semester })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch subjects');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                subjectDropdown.innerHTML = '<option value="" disabled selected>' + data.error + '</option>';
            } else {
                subjectDropdown.innerHTML = '<option value="" disabled selected>Select Subject</option>';
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject;
                    option.textContent = subject;
                    subjectDropdown.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching subjects:', error);
            subjectDropdown.innerHTML = '<option value="" disabled selected>Error fetching subjects</option>';
        });
}
    </script>
@endsection
