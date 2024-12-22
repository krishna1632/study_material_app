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

                <!-- Department Field -->
                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-control" required>
                        <option value="" disabled selected>Select Department</option>
                        <option value="Applied Psychology">Department of Applied Psychology</option>
                        <option value="Computer Science">Department of Computer Science</option>
                        <option value="B.voc(Software Development)">Department of B.voc (Software Development)</option>
                        <option value="Economics">Department of Economics</option>
                        <option value="English">Department of English</option>
                        <option value="Environmental Studies">Department of Environmental Studies</option>
                        <option value="Commerce">Department of Commerce</option>
                        <option value="Punjabi">Department of Punjabi</option>
                        <option value="Hindi">Department of Hindi</option>
                        <option value="History">Department of History</option>
                        <option value="Management Studies">Department of Management Studies</option>
                        <option value="Mathematics">Department of Mathematics</option>
                        <option value="Philosophy">Department of Philosophy</option>
                        <option value="Physical Education">Department of Physical Education</option>
                        <option value="Political Science">Department of Political Science</option>
                        <option value="Statistics">Department of Statistics</option>
                        <option value="B.voc(Software banking)">Department of B.voc (Software Banking)</option>
                    </select>
                </div>

                <!-- Title Field -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                        placeholder="Enter Material Title" required>
                </div>

                <!-- File Upload Field -->
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>

                <!-- Description Field -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control"
                        placeholder="Enter a brief description about the material" required></textarea>
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
