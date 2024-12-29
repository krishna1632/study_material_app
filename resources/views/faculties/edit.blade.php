@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <h1 class="mt-4">Edit User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.list') }}">User Management</a></li>
        <li class="breadcrumb-item active">Edit User</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-edit me-1"></i>
            Edit User Details
            <a href="{{ route('faculties.index') }}" class="btn btn-primary btn-sm float-end">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('faculties.update', $faculty->id) }}" method="POST">
                @csrf
                @method('POST')

                <!-- Faculty Name Field -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label font-weight-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        placeholder="Enter faculty name" value="{{ old('name', $faculty->name) }}" required>
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Faculty Email Field -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label font-weight-bold">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        placeholder="Enter faculty email" value="{{ old('email', $faculty->email) }}" required>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- faculty Phone Field -->
                <div class="form-group mb-3">
                    <label for="phone" class="form-label font-weight-bold">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                        placeholder="Enter phone number" value="{{ old('phone', $faculty->phone) }}" required>
                    @error('phone')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- faculty Department Field -->
                <div class="form-group mb-3">
                    <label for="department" class="form-label font-weight-bold">Department</label>
                    <select name="department" id="department" class="form-select" required>
                        <option value="" disabled>Select Department</option>
                        <option value="Applied Psychology" {{ old('department', $faculty->department) == 'Applied Psychology' ? 'selected' : '' }}>
                            Department of Applied Psychology
                        </option>
                        <option value="Computer Science" {{ old('department', $faculty->department) == 'Computer Science' ? 'selected' : '' }}>
                            Department of Computer Science
                        </option>
                        <option value="B.voc(Software Development)" {{ old('department', $faculty->department) == 'B.voc(Software Development)' ? 'selected' : '' }}>
                            Department of B.voc (Software Development)
                        </option>
                        <option value="Economics" {{ old('department', $faculty->department) == 'Economics' ? 'selected' : '' }}>
                            Department of Economics
                        </option>
                        <option value="English" {{ old('department', $faculty->department) == 'English' ? 'selected' : '' }}>
                            Department of English
                        </option>
                        <option value="Environmental Studies" {{ old('department', $faculty->department) == 'Environmental Studies' ? 'selected' : '' }}>
                            Department of Environmental Studies
                        </option>
                        <option value="Commerce" {{ old('department', $faculty->department) == 'Commerce' ? 'selected' : '' }}>
                            Department of Commerce
                        </option>
                        <option value="Punjabi" {{ old('department', $faculty->department) == 'Punjabi' ? 'selected' : '' }}>
                            Department of Punjabi
                        </option>
                        <option value="Hindi" {{ old('department', $faculty->department) == 'Hindi' ? 'selected' : '' }}>
                            Department of Hindi
                        </option>
                        <option value="History" {{ old('department', $faculty->department) == 'History' ? 'selected' : '' }}>
                            Department of History
                        </option>
                        <option value="Management Studies" {{ old('department', $faculty->department) == 'Management Studies' ? 'selected' : '' }}>
                            Department of Management Studies
                        </option>
                        <option value="Mathematics" {{ old('department', $faculty->department) == 'Mathematics' ? 'selected' : '' }}>
                            Department of Mathematics
                        </option>
                        <option value="Philosophy" {{ old('department', $faculty->department) == 'Philosophy' ? 'selected' : '' }}>
                            Department of Philosophy
                        </option>
                        <option value="Physical Education" {{ old('department', $faculty->department) == 'Physical Education' ? 'selected' : '' }}>
                            Department of Physical Education
                        </option>
                        <option value="Political Science" {{ old('department', $faculty->department) == 'Political Science' ? 'selected' : '' }}>
                            Department of Political Science
                        </option>
                        <option value="Statistics" {{ old('department', $faculty->department) == 'Statistics' ? 'selected' : '' }}>
                            Department of Statistics
                        </option>
                        <option value="B.voc(Banking)" {{ old('department', $faculty->department) == 'B.voc(Banking)' ? 'selected' : '' }}>
                            Department of B.voc (Banking)
                        </option>
                    </select>
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
                        <i class="fas fa-save me-1"></i> Update User
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
