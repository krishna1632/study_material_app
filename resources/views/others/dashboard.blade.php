@extends('layouts.user')

@section('content')
    <h1 class="mt-4" style="font-size:35px">Welcome,
        <span class="navbar-text me-3 mt-7 text-primary" style="font-size:35px">
            {{ Auth::user()->name }}
        </span>
    </h1>
    <ol class="breadcrumb mb-4"></ol>
    <div class="row">
        <!-- Profile Picture and Static Info (Left Card) profile photo key lieay -->
        <div class="container col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <!-- Profile Picture -->
                    <img src="{{ asset($other->profilePic) }}" alt="Profile Picture" class="rounded-circle" width="150"
                        height="150">
                    <h3 class="mt-3 text-primary">{{ $other->name }}</h3>
                    <hr>
                    <div class="mt-4">
                        <p><strong>College:</strong> Ramanujan College</p>
                        <hr>
                        <p><strong>Department:</strong> {{ $other->department }}</p>

                    </div>
                </div>
            </div>
        </div>

        <!-- Editable Form (Right Card) -->
        <div class="container col-md-8">
            <div class="card" style="font-weight: 700; font-size:17px">
                <div class="card-header">
                    User Profile
                </div>
                <div class="card-body">
                    <form action="{{ route('others.update', ['id' => Auth::user()->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Full Name (Read-only) -->
                        <div class="row mb-3">
                            <label for="fullName" class="col-sm-3 col-form-label">Full Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="fullName" value="{{ $other->name }}"
                                    readonly>
                            </div>
                        </div>

                        <!-- Email (Editable) -->
                        <div class="row mb-3">
                            <label for="email" class="col-sm-3 col-form-label">Email <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ $other->email }}">
                            </div>
                        </div>

                        <!-- Phone (Editable) -->
                        <div class="row mb-3">
                            <label for="phone" class="col-sm-3 col-form-label">Phone <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="phone" id="phone"
                                    value="{{ $other->phone }}">
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
                            <button type="submit" class="btn btn-warning">Update Profile</button>
                            <button type="button" class="btn btn-primary" onclick="togglePasswordForm()">Change
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form (Hidden initially) -->
            <div id="passwordForm" class="card mt-4" style="display: none;">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Change Password</h2>
                </div>
                <div class="card-body">
                    <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay
                        secure.</p>

                    <form id="updatePasswordForm" method="post" action="{{ route('password.update') }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="row mb-3">
                            <label for="update_password_current_password"
                                class="col-sm-4 col-form-label">{{ __('Current Password') }}</label>
                            <div class="col-sm-8">
                                <x-text-input id="update_password_current_password" name="current_password" type="password"
                                    class="form-control" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="row mb-3">
                            <label for="update_password_password"
                                class="col-sm-4 col-form-label">{{ __('New Password') }}</label>
                            <div class="col-sm-8">
                                <x-text-input id="update_password_password" name="password" type="password"
                                    class="form-control" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="row mb-3">
                            <label for="update_password_password_confirmation"
                                class="col-sm-4 col-form-label">{{ __('Confirm Password') }}</label>
                            <div class="col-sm-8">
                                <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                                    type="password" class="form-control" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Update Password Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">{{ __('Update Password') }}</button>
                        </div>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600 mt-3">
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
