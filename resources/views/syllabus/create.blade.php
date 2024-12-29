@extends('layouts.admin')

@section('content')
<div class="page-wrapper" style="margin-top:3rem;">
    <div class="page-content">
        <div class="card p-2">
            <div class="card-body">
                <h3 class="h2 mb-4">Upload Syllabus</h3>
                <form action="{{ route('syllabus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Subject Type -->
                        <div class="col-md-6">
                            <label for="subject_type">Subject Type<font color="red">*</font></label>
                            <select name="subject_type" id="subject_type" class="form-control" required>
                                <option value="" disabled selected>Select Subject Type</option>
                                @foreach ($subjectTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <label for="department">Department/Elective</label>
                            <select name="department" id="department" class="form-control">
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Semester -->
                        <div class="col-md-6 mt-3">
                            <label for="semester">Semester<font color="red">*</font></label>
                            <select name="semester" id="semester" class="form-control" required>
                                <option value="" disabled selected>Select Semester</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject -->
                        <div class="col-md-6 mt-3">
                            <label for="subject_name">Subject<font color="red">*</font></label>
                            <select name="subject_name" id="subject" class="form-control" required>
                                <option value="" disabled selected>Select Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject }}">{{ $subject }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- File -->
                        <div class="col-md-6 mt-3">
                            <label for="file">File<font color="red">*</font></label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>

                        <!-- Submit -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Trigger the subject filter based on selection changes
    $('#subject_type, #department, #semester').change(function() {
        var subjectType = $('#subject_type').val();
        let department = $('#department').val();
        var semester = $('#semester').val();

        // Logic to filter subjects based on the selected type
        if (subjectType && department && semester) {
            $.ajax({
                url: "{{ route('filter.subjects') }}",
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
        } 

        // Agar SEC, VAC, GEC, ya AEC ho aur sirf semester ho
        else if (subjectType &&  semester) {
            $.ajax({
                url: "{{ route('filter.subjects') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    subject_type: subjectType,
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
            // Agar koi bhi condition valid nahi hai, subject dropdown ko reset karo
            $('#subject').empty();
            $('#subject').append('<option value="" disabled selected>Select Subject</option>');
        }
    });

    // Logic to filter the department dropdown
    $('#subject_type').change(function() {
        var subjectType = $(this).val();
        var departmentSelect = $('#department');

        departmentSelect.empty(); // Clear existing options
        departmentSelect.append('<option value="" disabled selected>Select Department</option>');

        if (subjectType === 'CORE' || subjectType === 'DSE') {
            $.each(@json($departments), function(index, value) {
                if (value !== 'ELECTIVE') {
                    departmentSelect.append('<option value="' + value + '">' + value + '</option>');
                }
            });
        } else if (subjectType === 'VAC' || subjectType === 'SEC' || subjectType === 'GE' || subjectType === 'AEC') {
            departmentSelect.append('<option value="ELECTIVE">ELECTIVE</option>');
        }
    });

    // Common function to populate subject dropdown
    function populateSubjects(data) {
        var subjectSelect = $('#subject');
        subjectSelect.empty(); // Clear existing options
        subjectSelect.append('<option value="" disabled selected>Select Subject</option>');
        
        $.each(data, function(id, name) {
            subjectSelect.append('<option value="' + name + '">' + name + '</option>');
        });
    }
});


</script>
@endsection
