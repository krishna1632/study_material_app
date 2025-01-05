@extends('layouts.admin')

@section('title', 'Study Materials')

@section('content')
    <h1 class="mt-4">Study Materials</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Study Materials</li>
    </ol>
   
    <div class="card mb-4">
   
                
           
    <div class="card-header">
    <i class="fas fa-book me-1"></i>
    Study Materials List

    @can('create study material')
        <a href="{{ route('study_materials.create') }}" class="btn btn-primary btn-sm float-end">
            Add New Study Material
        </a>
    @endcan
    @if (auth()->user()->hasRole('student'))
    <a href="{{ route('study_materials.elective') }}" class="btn btn-primary btn-sm float-end me-2">
        View Elective Study Material
    </a>

@endif


</div>

        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Subject Type</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Subject Name</th>
                        <th>Faculty Name</th>
                        <th>File</th>
                        <th>Description</th>
                        @canany(['edit study material', 'delete study material'])
                        <th>Action</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @foreach ($study_materials as $index => $material)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $material->subject_type }}</td>
                            <td>{{ $material->department }}</td>
                            <td>{{ $material->semester }}</td>
                            <td>{{ $material->subject_name }}</td>
                            <td>{{ $material->faculty_name }}</td>
                            <td>
                                @if ($material->file)
                                    <a href="{{ asset('storage/' . $material->file) }}" target="_blank">
                                        <button class="btn btn-primary btn-sm">View File</button>
                                    </a>
                                @else
                                    No File
                                @endif
                            </td>
                            <td>{{ $material->description }}</td>
                            <td>
                                {{-- <a href="{{ route('study_materials.show', $material->id) }}" class="btn btn-info btn-sm">
                                    View
                                </a> --}}
                                @can('edit study material')
                                    <a href="{{ route('study_materials.edit', $material->id) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                @endcan
                                @can('delete study material')
                                    <!-- Delete Form -->
                                    <form id="delete-form-{{ $material->id }}"
                                        action="{{ route('study_materials.destroy', $material->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $material->id }})">Delete</button>
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

    <!-- SweetAlert Confirmation for Delete -->
    <script>
        function confirmDelete(materialId) {
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
                    document.getElementById('delete-form-' + materialId).submit();
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
