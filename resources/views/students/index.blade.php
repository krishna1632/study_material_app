@extends('layouts.admin')

@section('title', 'Student Management')

@section('content')
    <h1 class="mt-4">Student Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Students</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Students List
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($student->isNotEmpty())
                        @foreach ($student as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->roles->pluck('name')->implode(', ') }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $student->department }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('students.show', $student->id) }}"
                                        class="btn btn-sm btn-info">View</a>
                                    @can('edit students')
                                        <a href="{{ route('students.edit', $student->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('delete students')
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                            style="display:inline;" id="delete-form-{{ $student->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $student->id }})">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted">No students found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

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

    <script>
        function confirmDelete(studentId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trigger form submission
                    document.getElementById('delete-form-' + studentId).submit();
                }
            });
        }
    </script>
@endsection
