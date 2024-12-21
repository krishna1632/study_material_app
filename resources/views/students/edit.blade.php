@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
    <h1 class="mt-4">Edit Student</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Student Management</a></li>
        <li class="breadcrumb-item active">Edit Student</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-edit me-1"></i>
            Edit Student Details
            <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm float-end">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('POST')

                <!-- Student Name Field -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label font-weight-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        placeholder="Enter student name" value="{{ old('name', $student->name) }}" required>
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Student Email Field -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label font-weight-bold">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        placeholder="Enter student email" value="{{ old('email', $student->email) }}" required>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Student Phone Field -->
                <div class="form-group mb-3">
                    <label for="phone" class="form-label font-weight-bold">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                        placeholder="Enter phone number" value="{{ old('phone', $student->phone) }}" required>
                    @error('phone')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Student Department Field -->
                <div class="form-group mb-3">
                    <label for="department" class="form-label font-weight-bold">Department</label>
                    <input type="text" name="department" id="department" class="form-control"
                        placeholder="Enter department" value="{{ old('department', $student->department) }}" required>
                    @error('department')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Roles Checkboxes -->
                <div class="form-group mb-4">
                    <label for="roles" class="form-label font-weight-bold">Roles</label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        @if ($roles->isNotEmpty())
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input type="checkbox" name="role[]" id="role-{{ $role->id }}"
                                        value="{{ $role->name }}" class="form-check-input"
                                        {{ $hasRoles->contains($role->id) ? 'checked' : '' }}>
                                    <label for="role-{{ $role->id }}"
                                        class="form-check-label">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No roles available.</p>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Update Student
                    </button>
                </div>
            </form>
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
@endsection
