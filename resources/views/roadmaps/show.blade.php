@extends('layouts.admin')

@section('title', 'Roadmap Details')

@section('content')
    <h1 class="mt-4">Roadmap Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roadmaps.index') }}">Roadmaps</a></li>
        <li class="breadcrumb-item active">Roadmap Details</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Roadmap Information
        </div>
        {{-- {{ dd($roadmap) }} --}}
        <div class="card-body">
            <div class="mb-3">
                <strong>Department:</strong>
                <span>{{ $roadmap->department }}</span>
            </div>
            <div class="mb-3">
                <strong>Title:</strong>
                <span>{{ $roadmap->title }}</span>
            </div>
            <div class="mb-3">
                <strong>Description:</strong>
                <span>{{ $roadmap->description }}</span>
            </div>
            <div class="mb-3">
                        
                       
                        <p class="mt-3"><small style="font-weight: 500;">Current File: </small>
                            <a href="{{ asset('storage/' . $roadmap->file) }}" target="_blank"
                                class="btn btn-outline-primary">
                                <i class="fas fa-file-alt me-2"></i>View File
                            </a>
                        </p>

                    </div>
        </div>
    </div>

    <div class="text-end">
        <a href="{{ route('roadmaps.index') }}" class="btn btn-secondary">Back</a>
        <!-- <a href="{{ route('roadmaps.edit', $roadmap->id) }}" class="btn btn-warning">Edit</a> -->

    </div>
@endsection
