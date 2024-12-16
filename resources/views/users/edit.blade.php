@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-weight-bold">{{ __('Edit User') }}</h2>
        <a href="{{ route('users.list') }}" class="btn btn-sm btn-primary">Back</a>
    </div>

    {{-- Edit User Form --}}
    <div class="card shadow mt-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('POST')

                <!-- User Name Field -->
                <div class="form-group my-3">
                    <label for="name" class="text-lg font-medium">Name</label>
                    <input value="{{ old('name', $user->name) }}" placeholder="Enter user name" type="text"
                        name="name" class="form-control w-50" required>
                    @error('name')
                        <p class="text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Email Field -->
                <div class="form-group my-3">
                    <label for="email" class="text-lg font-medium">Email</label>
                    <input value="{{ old('email', $user->email) }}" placeholder="Enter email" type="email"
                        name="email" class="form-control w-50" required>
                    @error('email')
                        <p class="text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Roles Checkboxes -->
                <div class="form-group mt-4">
                    <label for="roles" class="text-lg font-medium">Roles</label>
                    <div class="grid grid-cols-4 gap-4 mt-3">
                        @if ($roles->isNotEmpty())
                            @foreach ($roles as $role)
                                <div>
                                    <input type="checkbox" name="role[]" id="role-{{ $role->id }}"
                                        value="{{ $role->name }}" class="rounded"
                                        {{ $hasRoles->contains($role->id) ? 'checked' : '' }}>
                                    <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No roles available.</p>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-4">
                    Update User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
