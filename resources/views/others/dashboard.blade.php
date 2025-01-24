@extends('layouts.user')

@section('content')
    <h1 class="mt-4" style="font-size:35px">Welcome,
        <span class="navbar-text me-3 mt-7 text-primary" style="font-size:35px">
            {{ Auth::user()->name }}
        </span>
    </h1>
    <ol class="breadcrumb mb-4"></ol>
    <div class="row justify-content-center mt-5">
        <!-- Profile Picture and Static Info (Enhanced Design) -->
        <div class="col-md-4">
            <div class="card text-center shadow-lg border-0 rounded-4" style="background-color: #f8f9fa;">
                <div class="card-body p-4">
                    <!-- Profile Picture -->
                    <div class="profile-pic-wrapper mb-4">
                        <img src="{{ asset($other->profilePic) }}" alt="Profile Picture" class="rounded-circle border border-4 border-dark shadow-sm" width="150" height="150">
                    </div>
                    <h3 class="mt-3 font-weight-bold" style="font-family: 'Arial', sans-serif; color: #343a40;">{{ $other->name }}</h3>
                    <hr class="my-4" style="border-color: #dee2e6;">
                    <div class="static-info mt-4">
                        <p class="mb-3"><strong>College:</strong> <span style="color: #6c757d;">Ramanujan College</span></p>
                        <hr class="w-75 mx-auto" style="border-color: #dee2e6;">
                        <p class="mb-0"><strong>Department:</strong> <span style="color: #6c757d;">{{ $other->department }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Editable Form (Right Card) -->
        <div class="container col-md-8">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4">
                    <h5 class="mb-0">User Profile</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('others.update', ['id' => Auth::user()->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Full Name (Read-only) -->
                        <div class="row mb-3">
                            <label for="fullName" class="col-sm-3 col-form-label">Full Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="fullName" value="{{ $other->name }}" readonly>
                            </div>
                        </div>

                        <!-- Email (Editable) -->
                        <div class="row mb-3">
                            <label for="email" class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" id="email" value="{{ $other->email }}">
                            </div>
                        </div>

                        <!-- Phone (Editable) -->
                        <div class="row mb-3">
                            <label for="phone" class="col-sm-3 col-form-label">Phone <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="phone" id="phone" value="{{ $other->phone }}">
                            </div>
                        </div>

                        <!-- Profile Picture Upload -->
                        <div class="row mb-3">
                            <label for="profilePic" class="col-sm-3 col-form-label">Profile Picture</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="profilePic" id="profilePic">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-dark">Update Profile</button>
                            <button type="button" class="btn btn-secondary" onclick="togglePasswordForm()">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form (Hidden initially) -->
            <div id="passwordForm" class="card mt-4 shadow-lg rounded-4" style="display: none;">
                <div class="card-header bg-secondary text-white rounded-top-4">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body p-4">
                    <p class="mt-1 text-sm text-muted">Ensure your account is using a long, random password to stay secure.</p>

                    <form id="updatePasswordForm" method="post" action="{{ route('password.update') }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="row mb-3">
                            <label for="update_password_current_password" class="col-sm-4 col-form-label">{{ __('Current Password') }}</label>
                            <div class="col-sm-8">
                                <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="row mb-3">
                            <label for="update_password_password" class="col-sm-4 col-form-label">{{ __('New Password') }}</label>
                            <div class="col-sm-8">
                                <x-text-input id="update_password_password" name="password" type="password" class="form-control" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="row mb-3">
                            <label for="update_password_password_confirmation" class="col-sm-4 col-form-label">{{ __('Confirm Password') }}</label>
                            <div class="col-sm-8">
                                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Update Password Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">{{ __('Update Password') }}</button>
                        </div>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-success mt-3">
                                {{ __('Saved.') }}
                            </p>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Success Popup -->
    @if (session('success'))
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated Successfully!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    width: '600px',
                    padding: '3em',
                    backdrop: `rgba(0,0,0,0.4)`,
                    position: 'center',
                    customClass: {
                        popup: 'my-swal-popup'
                    }
                });
            }
        </script>
    @endif

    <!-- SweetAlert Error Popup for Password Update -->
    @if ($errors->updatePassword->any())
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Update Failed!',
                    text: "{{ $errors->updatePassword->first() }}",
                    showConfirmButton: true,
                    width: '600px',
                    padding: '3em',
                    backdrop: `rgba(0,0,0,0.4)`,
                    position: 'center',
                    customClass: {
                        popup: 'my-swal-popup'
                    }
                });
            }
        </script>
    @endif

    <!-- JavaScript to toggle the Change Password form -->
    <script>
        function togglePasswordForm() {
            const passwordForm = document.getElementById('passwordForm');
            passwordForm.style.display = passwordForm.style.display === 'none' ? 'block' : 'none';
        }

        // Success popup for password update
        @if (session('status') === 'password-updated')
            window.onload = function() {
                const passwordForm = document.getElementById('passwordForm');
                passwordForm.style.display = 'none'; // Hide the password form
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your password has been updated successfully.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    width: '600px',
                    padding: '3em',
                    backdrop: `rgba(0,0,0,0.4)`,
                    position: 'center',
                    customClass: {
                        popup: 'my-swal-popup'
                    }
                });
            }
        @endif
    </script>

    <!-- CSS to further ensure the popup centers correctly -->
    <style>
        .my-swal-popup {
            margin: 0 auto !important;
            /* Center-aligns popup on all screen sizes */
        }
    </style>
@endsection
