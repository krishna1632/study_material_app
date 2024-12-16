@extends('layouts.admin')

@section('title', 'Permission Management')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between">
            <h2 class="font-weight-bold">Permission Management</h2>
            {{-- Create button --}}
            @can('create permissions')
                <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create
                </a>
            @endcan
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($permissions->isNotEmpty())
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permission->created_at)->format('d M, Y') }}</td>
                                    <td class="text-center">
                                        {{-- Edit button --}}
                                        @can('edit permissions')
                                            <a href="{{ route('permissions.edit', $permission->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endcan

                                        {{-- Delete button --}}
                                        @can('delete permissions')
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-muted">No Permissions Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{-- Pagination --}}
                {{-- <div class="d-flex justify-content-between mt-4">
                    <div>
                        {{ $permissions->links('vendor.pagination.bootstrap-4') }}
                    </div>
                    <div>
                        <p class="text-muted">
                            Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of {{ $permissions->total() }} entries
                        </p>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection
