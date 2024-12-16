<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit Role
            </h2>
            <a href="{{ route('roles.index') }}" class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        <div>
                            <!-- Role Name Field -->
                            <label for="name" class="text-lg font-medium">Role Name</label>
                            <div class="my-3">
                                <input value="{{ old('name', $role->name) }}" placeholder="Enter Role Name"
                                    type="text" name="name"
                                    class="border-gray-300 shadow-sm w-1/2 rounded-lg text-black" required>
                                @error('name')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Permissions Checkboxes -->
                            <div>
                                <label for="permissions" class="text-lg font-medium">Permissions</label>
                                <div class="grid grid-cols-4 gap-4 mt-3">
                                    @if ($permissions->isNotEmpty())
                                        @foreach ($permissions as $permission)
                                            <div>
                                                <input type="checkbox" name="permissions[]"
                                                    id="permission-{{ $permission->id }}"
                                                    value="{{ $permission->name }}" class="rounded"
                                                    @if ($hasPermissions->contains($permission->name)) checked @endif>
                                                <label
                                                    for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-gray-500">No permissions available.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="bg-slate-700 mt-5 text-sm rounded-md px-5 py-3 text-white">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
