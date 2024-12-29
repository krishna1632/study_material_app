@extends('layouts.admin')

@section('title', 'Syllabus')

@section('content')
    <h1 class="mt-4">Syllabus</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Syllabus</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-book me-1"></i>
            Syllabus List
            @can('create syllabus')
            <a href="{{ route('syllabus.create') }}" class="btn btn-primary btn-sm float-end">Add New Syllabus</a>
            @endcan
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Type</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Subject Name</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($syllabus as $material)
                        <tr>
                            <td>{{ $material->subject_type }}</td>
                            <td>{{ $material->department }}</td>
                            <td>{{ $material->semester }}</td>
                            <td>{{ $material->subject_name }}</td>
                            <td>
                                @if ($material->file)
                                    <a href="{{ asset('storage/' . $material->file) }}" target="_blank" class="btn btn-primary btn-sm">
                                        View File
                                    </a>
                                @else
                                    No File
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('syllabus.show',$material->id) }}" class="btn btn-info btn-sm">
                                    View
                                </a>
                                @can('edit syllabus')
                                <a href="{{ route('syllabus.edit',$material->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                @endcan
                                @can('delete sylabus')
                                <form id="delete-form-{{ $material->id }}" action="{{ route('syllabus.destroy',$material->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $material->id }})">
                                    Delete
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

    <!-- SweetAlert Confirmation for Delete -->
    <script>
        function confirmDelete(id) {
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
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endsection
