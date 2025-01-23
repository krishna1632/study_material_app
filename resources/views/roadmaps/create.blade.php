@extends('layouts.admin')

@section('title', 'Roadmaps')

@section('content')
    <h1 class="mt-4">Add New Roadmaps</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Add Roadsmaps</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Add new Roadmap
        </div>
        <div class="card-body">
            <form action="{{ route('roadmaps.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="department" class="form-label">Department</label>
                        @if (count($departments) > 1)
                            <select name="department" id="department" class="form-control" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}">{{ $department }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="department" id="department" class="form-control"
                                value="{{ $departments[0] }}" readonly>
                        @endif
                    </div>

                    <!-- Title Field -->
                    <div class="col-md-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            placeholder="Enter Material Title" required>
                    </div>

                    <!-- File Upload Field -->
                    <div class="col-md-4">
                        <label for="file" class="form-label">Upload File</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            placeholder="Enter a brief description about the material" required></textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="text-end">
                    <a href="{{ route('roadmaps.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include SweetAlert Success Popup -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
