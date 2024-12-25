@extends('layouts.admin')

@section('title', 'Register User')

@section('content')
    <h1 class="mt-4">Register New User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Register User</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-plus me-1"></i>
            Register New User
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}" class="form-horizontal">
                @csrf

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                        required autofocus>
                    @error('name')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}"
                        required>
                    @error('phone')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Department Field -->
                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-select" required>
                        <option value="" disabled selected>Select Department</option>
                        <option value="Applied Psychology">Department of Applied
                            Psychology</option>
                        <option value="Computer Science">
                            Department of Computer Science</option>
                        <option value="B.voc(Software Development)">Department of B.voc
                            (Software Development)</option>
                        <option value="Economics">Department of
                            Economics</option>
                        <option value="English">Department of
                            English</option>
                        <option value="Environmental Studies">Department of
                            Environmental Studies</option>
                        <option value="Commerce">Department of
                            Commerce</option>
                        <option value="Punjabi">Department of
                            Punjabi</option>
                        <option value="Hindi">Department of Hindi
                        </option>
                        <option value="History">Department of
                            History</option>
                        <option value="Management Studies">Department of Management
                            Studies</option>
                        <option value="Mathematics">Department
                            of Mathematics</option>
                        <option value="Philosophy">Department of
                            Philosophy</option>
                        <option value="Physical Education">Department of Physical
                            Education</option>
                        <option value="Political Science">
                            Department of Political Science</option>
                        <option value="Statistics">Department of
                            Statistics</option>
                        <option value="admin">Admin</option>
                        <option value="B.voc(Software banking)">Department of B.voc
                            (Banking)</option>
                    </select>
                    @error('department')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                    <!-- Assign Role Field -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Assign Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="" disabled selected>Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>


                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    @error('password')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        required>
                    @error('password_confirmation')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.list') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Back index page
                    </a>
                    <button type="submit" class="btn btn-primary">Register</button>
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
