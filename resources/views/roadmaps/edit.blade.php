@extends('layouts.admin')

@section('title', 'Edit Roadmap')

@section('content')
    <h1 class="mt-4">Edit Roadmap</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roadmaps.index') }}">Roadmaps</a></li>
        <li class="breadcrumb-item active">Edit Roadmap</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit me-1"></i>
            Edit Roadmap Details
        </div>
        <div class="card-body">
            <form action="{{ route('roadmaps.update', Crypt::encryptString($roadmap->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') <!-- Correct HTTP Method for Update -->

                <!-- Department Field -->
                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-control" required>
                        <option value="" disabled>Select Department</option>
                        <option value="Applied Psychology"
                            {{ $roadmap->department == 'Applied Psychology' ? 'selected' : '' }}>
                            Department of Applied Psychology
                        </option>
                        <option value="Computer Science" {{ $roadmap->department == 'Computer Science' ? 'selected' : '' }}>
                            Department of Computer Science
                        </option>
                        <option value="B.voc(Software Development)"
                            {{ $roadmap->department == 'B.voc(Software Development)' ? 'selected' : '' }}>
                            Department of B.voc (Software Development)
                        </option>
                        <option value="Economics" {{ $roadmap->department == 'Economics' ? 'selected' : '' }}>
                            Department of Economics
                        </option>
                        <option value="English" {{ $roadmap->department == 'English' ? 'selected' : '' }}>
                            Department of English
                        </option>
                        <option value="Environmental Studies"
                            {{ $roadmap->department == 'Environmental Studies' ? 'selected' : '' }}>
                            Department of Environmental Studies
                        </option>
                        <option value="Commerce" {{ $roadmap->department == 'Commerce' ? 'selected' : '' }}>
                            Department of Commerce
                        </option>
                        <option value="Punjabi" {{ $roadmap->department == 'Punjabi' ? 'selected' : '' }}>
                            Department of Punjabi
                        </option>
                        <option value="Hindi" {{ $roadmap->department == 'Hindi' ? 'selected' : '' }}>
                            Department of Hindi
                        </option>
                        <option value="History" {{ $roadmap->department == 'History' ? 'selected' : '' }}>
                            Department of History
                        </option>
                        <option value="Management Studies"
                            {{ $roadmap->department == 'Management Studies' ? 'selected' : '' }}>
                            Department of Management Studies
                        </option>
                        <option value="Mathematics" {{ $roadmap->department == 'Mathematics' ? 'selected' : '' }}>
                            Department of Mathematics
                        </option>
                        <option value="Philosophy" {{ $roadmap->department == 'Philosophy' ? 'selected' : '' }}>
                            Department of Philosophy
                        </option>
                        <option value="Physical Education"
                            {{ $roadmap->department == 'Physical Education' ? 'selected' : '' }}>
                            Department of Physical Education
                        </option>
                        <option value="Political Science"
                            {{ $roadmap->department == 'Political Science' ? 'selected' : '' }}>
                            Department of Political Science
                        </option>
                        <option value="Statistics" {{ $roadmap->department == 'Statistics' ? 'selected' : '' }}>
                            Department of Statistics
                        </option>
                        <option value="B.voc(Software Banking)"
                            {{ $roadmap->department == 'B.voc(Software Banking)' ? 'selected' : '' }}>
                            Department of B.voc (Software Banking)
                        </option>
                    </select>
                </div>

                <!-- Title Field -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $roadmap->title }}"
                        required>
                </div>

                <!-- File Upload Field (optional) -->
                <div class="mb-3">
                    <label for="file" class="form-label">Upload New File (optional)</label>
                    <input type="file" name="file" id="file" class="form-control">
                    <small class="text-muted">Current file:
                        @if ($roadmap->file)
                            <a href="{{ asset('storage/' . $roadmap->file) }}" target="_blank">View File</a>
                        @else
                            No file uploaded
                        @endif
                    </small>
                </div>

                <!-- Description Field -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control" required>{{ $roadmap->description }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="text-end">
                    <a href="{{ route('roadmaps.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Popup with SweetAlert -->
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
