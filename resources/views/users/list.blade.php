<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Users') }}
            </h2>
            {{-- @can('create roles') --}}
            {{-- <a href="{{ route('roles.create') }}" class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Create</a> --}}
            {{-- @endcan --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left" width="60">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left" width="180">Created</th>
                        <th class="px-6 py-3 text-center" width="180">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($users->isNotEmpty())
                        @foreach ($users as $user)
                            <tr class="border-b">
                                <td class="px-6 py-3 text-left">
                                    {{ $user->id }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ $user->roles->pluck('name')->implode(' , ') }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @can('edit roles')
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="bg-slate-700 text-sm rounded-md px-3 py-2 text-white hover:bg-slate-600">Edit</a>
                                    @endcan
                                    {{-- @can('delete roles') --}}
                                    {{-- <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                        class="delete-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="bg-red-600 text-sm rounded-md px-3 py-2 text-white hover:bg-red-500 delete-btn">
                                            Delete
                                        </button>
                                    </form> --}}
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-3 text-gray-500">No roles found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="my-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- SweetAlert2 Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Success Flash Message
        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 3000, // Popup will close after 3 seconds
                showConfirmButton: false // Hides the "OK" button
            });
        @endif

        // Error Flash Message
        @if (session('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                timer: 3000, // Popup will close after 3 seconds
                showConfirmButton: false // Hides the "OK" button
            });
        @endif

        // Delete Confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default form submission
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form if confirmed
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
