@extends('layouts.admin')

@section('title', 'Quiz Reports')

@section('content')
    <h1 class="mt-4">Quiz Reports</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            @can('is superadmin')
                <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('others.dashboard') }}">Dashboard</a>
            @endcan
        </li>
        <li class="breadcrumb-item active">Quiz Reports</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="quizForm" action="{{ route('quiz_reports.fetch-quizzes') }}" method="POST" class="needs-validation"
                novalidate>
                @csrf
                <div class="row g-3 align-items-center">
                    <!-- Subject Type -->
                    <div class="col-md-2">
                        <label for="subject_type" class="form-label">Subject Type</label>
                        <select name="subject_type" id="subject_type" class="form-control form-select-lg" required>
                            <option value="" disabled selected>Select</option>
                            <option value="CORE">CORE</option>
                            <option value="SEC">SEC</option>
                            <option value="VAC">VAC</option>
                            <option value="GE">GE</option>
                            <option value="AEC">AEC</option>
                            <option value="DSE">DSE</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div class="col-md-2">
                        <label for="department" class="form-label">Department</label>
                        <select name="department" id="department" class="form-control form-select-lg" required>
                            <option value="" disabled selected>Select</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="col-md-2">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-control form-select-lg" required>
                            <option value="" disabled selected>Select</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}"> {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Subject Name -->
                    <div class="col-md-3">
                        <label for="subject_name" class="form-label">Subject</label>
                        <select name="subject_name" id="subject_name" class="form-control form-select-lg" required>
                            <option value="" disabled selected>Select</option>
                        </select>
                    </div>

                    <!-- Faculty Name -->
                    <div class="col-md-3">
                        <label for="faculty_name" class="form-label">Faculty Name</label>
                        <select name="faculty_name" id="faculty_name" class="form-select form-select-lg" required>
                            <option value="" disabled selected>Select</option>
                            @if ($roles->contains('Admin') || $roles->contains('SuperAdmin'))
                                <option value="Admin">Admin</option>
                            @endif
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->name }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-lg btn-primary">Find</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quizzes List -->
    @if (isset($quizzes) && $quizzes->count() > 0)
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Quizzes List</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Quiz ID</th>
                            <th>Subject Name</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->id }}</td>
                                <td>{{ $quiz->subject_name }}</td>
                                <td>{{ $quiz->created_at }}</td>
                                <td>
                                    <a href="{{ route('quiz_reports.viewResults', ['quiz_id' => $quiz->id]) }}"
                                        class="btn btn-sm btn-success">View Results</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-center mt-4">No quizzes found matching the criteria.</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#subject_type, #department, #semester').change(function() {
                var subjectType = $('#subject_type').val();
                let department = $('#department').val();
                var semester = $('#semester').val();

                if (subjectType && department && semester) {
                    $.ajax({
                        url: "/filter-subjects",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            subject_type: subjectType,
                            department: department,
                            semester: semester
                        },
                        success: function(data) {
                            populateSubjects(data);
                        },
                        error: function(error) {
                            console.log(error);
                            alert('Error fetching subjects');
                        }
                    });
                } else {
                    $('#subject_name').empty();
                    $('#subject_name').append('<option value="" disabled selected>Select Subject</option>');
                }
            });

            function populateSubjects(data) {
                var subjectSelect = $('#subject_name');
                subjectSelect.empty();
                subjectSelect.append('<option value="" disabled selected>Select Subject</option>');

                $.each(data, function(id, name) {
                    subjectSelect.append('<option value="' + name + '">' + name + '</option>');
                });
            }
        });
    </script>
@endsection
