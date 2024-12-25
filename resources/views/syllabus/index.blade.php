@extends('layouts.admin')

@section('title', 'Syllabus')

@section('content')
    <h1 class="mt-4">Syllabus</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Syllabus</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-book me-1"></i>
            Syllabus List
            <a href="{{ route('syllabus.create') }}" class="btn btn-primary btn-sm float-end">Add New Syllabus</a>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Type</th>
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
                            <td>
                                @if ($material->file)
                                    <a href="{{ asset('storage/' . $material->file) }}" target="_blank">
                                        <button class="btn btn-primary btn-sm">View File</button>
                                    </a>
                                @else
                                    No File
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('syllabus.show', $material) }}" class="btn btn-info btn-sm">
                                    View
                                </a>

                                <a href="{{ route('syllabus.edit', $material->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="#" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this study material?')">Delete</button>
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
