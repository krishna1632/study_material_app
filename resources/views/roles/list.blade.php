@extends('layouts.admin')

@section('title', 'Roles Management')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-weight-bold">Roles Management</h2>
        {{-- Create Button --}}
        @can('create roles')
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Role
            </a>
        @endcan
    </div>

    {{-- Roles Table --}}
    <div class="card shadow mt-4">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($roles->isNotEmpty())
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @if ($role->permissions->isNotEmpty())
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($role->permissions as $permission)
                                                <li><span class="">{{ $permission->name }} ,</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">No permissions assigned</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    {{-- Edit Button --}}
                                    @can('edit roles')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endcan

                                    {{-- Delete Button --}}
                                    @can('delete roles')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Roles Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
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
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Error Flash Message
    @if (session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Delete Confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
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
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    .custom-badge {
        background-color: #f39c12; /* Change this to your desired color */
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
@endsection
