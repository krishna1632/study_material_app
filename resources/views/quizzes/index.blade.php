@extends('layouts.admin')

@section('title', 'Quiz Management')

@section('content')
    <h1 class="mt-4">Quizzes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Quizzes</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-question-circle me-1"></i>
            @can('create quizzes')
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary btn-sm float-end">Create Quiz</a>
            @endcan
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
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
                    @foreach ($quizzes as $index => $quiz)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $quiz->subject_type }}</td>
                            <td>{{ $quiz->department }}</td>
                            <td>{{ $quiz->semester }}</td>
                            <td>{{ $quiz->subject_name }}</td>
                            <td>{{ $quiz->faculty_name }}</td>
                            <td>{{ $quiz->date }}</td>
                            <td>{{ $quiz->start_time }}</td>
                            <td>{{ $quiz->end_time }}</td>
                            <td>
                                @if ($quiz->questions && $quiz->questions->where('is_submitted', 1)->count() > 0)
                                    <button class="btn btn-secondary btn-sm" disabled>Questions Finalized</button>
                                @else
                                    <a href="{{ route('questions.index', $quiz->id) }}" class="btn btn-primary btn-sm">Add
                                        Question</a>
                                @endif
                                <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-info btn-sm">Preview and
                                    Start</a>
                                <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $quiz->id }})">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Confirmation for Delete -->
    <script>
        function confirmDelete(quizId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector(`form[action='/quizzes/${quizId}']`).submit();
                }
            });
        }
    </script>

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
@endsection
