<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit User
            </h2>
            <a href="{{ route('users.list') }}" class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <!-- User Name Field -->
                        <div class="my-3">
                            <label for="name" class="text-lg font-medium">Name</label>
                            <input value="{{ old('name', $user->name) }}" placeholder="Enter user name" type="text"
                                name="name" class="border-gray-300 shadow-sm w-1/2 rounded-lg text-black" required>
                            @error('name')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- User Email Field -->
                        <div class="my-3">
                            <label for="email" class="text-lg font-medium">Email</label>
                            <input value="{{ old('email', $user->email) }}" placeholder="Enter email" type="email"
                                name="email" class="border-gray-300 shadow-sm w-1/2 rounded-lg text-black" required>
                            @error('email')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Roles Checkboxes -->
                        <div class="mt-5">
                            <label for="roles" class="text-lg font-medium">Roles</label>
                            <div class="grid grid-cols-4 gap-4 mt-3">
                                @if ($roles->isNotEmpty())
                                    @foreach ($roles as $role)
                                        <div>
                                            {{--  {{ $hasRoles->contains($role->id) ? 'checked' : '' }} --}}
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
                        <button type="submit" class="bg-slate-700 mt-5 text-sm rounded-md px-5 py-3 text-white">
                            Update User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
