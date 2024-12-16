@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-weight-bold">{{ __('Users') }}</h2>
        {{-- Uncomment when the create button is needed --}}
        {{-- @can('create roles') --}}
        {{-- <a href="{{ route('roles.create') }}" class="btn btn-primary">Create</a> --}}
        {{-- @endcan --}}
    </div>

    {{-- Users Table --}}
    <div class="card shadow mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isNotEmpty())
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    @can('edit roles')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                    @endcan
                                    {{-- Uncomment when delete button is needed --}}
                                    {{-- @can('delete roles') --}}
                                    {{-- <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="delete-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn">
                                            Delete
                                        </button>
                                    </form> --}}
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">No users found</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="my-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
