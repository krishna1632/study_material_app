@extends('layouts.admin')

@section('title', 'Edit Syllabus')

@section('content')
    <div class="page-wrapper" style="margin-top:3rem;">
        <div class="page-content">
            <div class="card p-2">
                <div class="card-body">
                    <h3 class="h2 mb-4">Edit Syllabus</h3>
                    <form action="{{ route('syllabus.update', $syllabus->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Subject Type Dropdown -->
                            <div class="col-md-6">
                                <label for="subject_type" class="form-label">Subject Type</label>
                                <select name="subject_type" id="subject_type" class="form-control" required>
                                    <option value="" disabled>Select Subject Type</option>
                                    <option value="Core"
                                        {{ old('subject_type', $syllabus->subject_type) == 'Core' ? 'selected' : '' }}>Core
                                    </option>
                                    <option value="SEC"
                                        {{ old('subject_type', $syllabus->subject_type) == 'SEC' ? 'selected' : '' }}>SEC
                                    </option>
                                    <option value="VAC"
                                        {{ old('subject_type', $syllabus->subject_type) == 'VAC' ? 'selected' : '' }}>VAC
                                    </option>
                                    <option value="GE"
                                        {{ old('subject_type', $syllabus->subject_type) == 'GE' ? 'selected' : '' }}>GE
                                    </option>
                                    <option value="AEC"
                                        {{ old('subject_type', $syllabus->subject_type) == 'AEC' ? 'selected' : '' }}>AEC
                                    </option>
                                    <option value="DSE"
                                        {{ old('subject_type', $syllabus->subject_type) == 'DSE' ? 'selected' : '' }}>DSE
                                    </option>
                                </select>
                            </div>

                            <!-- Subject Name Dropdown (Dynamic) -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Subject Name</label>
                                <select name="name" id="subject_name" class="form-control" required>
                                    <option value="" disabled>Select Subject Name</option>
                                    <!-- Dynamically populated options based on selected subject type -->
                                </select>
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-6 mt-3">
                                <label for="file" class="form-label">File</label>
                                <input type="file" name="file" class="form-control-file">
                                @if ($syllabus->file)
                                    <p>Current File: <a href="{{ asset('storage/' . $syllabus->file) }}"
                                            target="_blank">View File</a></p>
                                @endif
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('syllabus.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update Syllabus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert CSS and JS -->
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
                    timerProgressBar: true,
                });
            }
        </script>
    @endif

    <!-- JavaScript for Dynamic Subject Options -->
    <script>
        const subjectOptions = {
            'Core': ['BMS', 'B.Voc Software Development', 'B.Com Hons'],
            'SEC': ['Frontend', 'Analytics with Python', 'Blockchain'],
            'VAC': ['Vedic Maths 1', 'Vedic Maths 2', 'Digital Empowerment'],
            'GE': ['Maths', 'CS', 'Management'],
            'AEC': ['EVS 1', 'Hindi-C', 'EVS 2'],
            'DSE': ['DIP', 'Big Data']
        };

        document.getElementById('subject_type').addEventListener('change', function() {
            const subjectType = this.value;
            const subjectNameSelect = document.getElementById('subject_name');
            subjectNameSelect.innerHTML = '<option value="" disabled selected>Select Subject Name</option>';

            if (subjectOptions[subjectType]) {
                subjectOptions[subjectType].forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject;
                    option.textContent = subject;
                    option.selected = subject === "{{ $syllabus->name }}";
                    subjectNameSelect.appendChild(option);
                });
            }
        });

        // Trigger change event on page load to populate the initial options
        document.getElementById('subject_type').dispatchEvent(new Event('change'));
    </script>
@endsection
