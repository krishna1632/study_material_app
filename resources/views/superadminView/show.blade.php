@extends('layouts.admin')

@section('title', 'View SuperAdmin')

@section('content')
    <h1 class="mt-4">View SuperAdmin</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/superadmin/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('superadminView.index') }}">SuperAdmins</a></li>
        <li class="breadcrumb-item active">{{ $super->name }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-shield me-1"></i>
            SuperAdmin Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $super->id }}</p>
                    <p><strong>Name:</strong> {{ $super->name }}</p>
                    <p><strong>Email:</strong> {{ $super->email }}</p>
                    <p><strong>Phone:</strong> {{ $super->phone }}</p>
                    <p><strong>Department:</strong> {{ $super->department }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Roles:</strong>
                        @foreach ($super->roles as $role)
                            <span class="badge bg-info">{{ $role->name }}</span>
                        @endforeach
                    </p>
                    <p><strong>Created At:</strong> {{ \Carbon\Carbon::parse($super->created_at)->format('d M, Y') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('superadminView.index') }}" class="btn btn-secondary btn-sm">Back to SuperAdmin List</a>
        </div>
    </div>
@endsection
