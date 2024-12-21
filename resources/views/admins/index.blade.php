@extends('layouts.admin')

@section('title', 'Admin Management')

@section('content')
    <h1 class="mt-4">Admin Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Admins</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Admin List
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Phone</th> <!-- Added Phone -->
                        <th>Department</th> <!-- Added Department -->
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($admins->isNotEmpty())
                        @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->roles->pluck('name')->implode(', ') }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->phone }}</td> <!-- Display Phone -->
                                <td>{{ $admin->department }}</td> <!-- Display Department -->
                                <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admins.show', $admin->id) }}" class="btn btn-info btn-sm">View</a>
                                    @can('edit admins')
                                        <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('delete admins')
                                        <form id="delete-form-{{ $admin->id }}"
                                            action="{{ route('admins.destroy', $admin->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $admin->id }})">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted">No admins found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Confirmation for Delete -->
    <script>
        function confirmDelete(adminId) {
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
                    // Submit the delete form
                    document.getElementById('delete-form-' + adminId).submit();
                }
            });
        }
    </script>

    <!-- SweetAlert Success Popup -->
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
