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
                            <div class="col-md-6">
                                <label for="subject_type" class="form-label">Subject Type<font color="red"><b>*</b>
                                    </font></label>
                                <select name="subject_type" id="subject_type" class="form-control" required>
                                    <option value="" disabled selected>Select Subject Type</option>
                                    @foreach (array_keys($subjectTypes) as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="department" class="form-label">Subject/Course Name<font color="red"><b>*</b>
                                    </font></label>
                                <select name="department" id="department" class="form-control" required>
                                    <option value="" disabled selected>Select name</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="file" class="form-label">File<font color="red"><b>*</b></font></label>
                                <input type="file" name="file" class="form-control" required>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ url('/syllabus') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Define subject data in JavaScript
        const subjectTypes = @json($subjectTypes);

        // Listen for changes in Subject Type dropdown
        document.getElementById('subject_type').addEventListener('change', function() {
            const selectedType = this.value;
            const departmentDropdown = document.getElementById('department'); // Corrected ID

            // Clear current options
            departmentDropdown.innerHTML = '<option value="" disabled selected>Select name</option>';

            // Add new options based on selected type
            if (subjectTypes[selectedType]) {
                subjectTypes[selectedType].forEach(function(subject) {
                    const option = document.createElement('option');
                    option.value = subject;
                    option.textContent = subject;
                    departmentDropdown.appendChild(option);
                });
            }
        });
    </script>
@endsection
