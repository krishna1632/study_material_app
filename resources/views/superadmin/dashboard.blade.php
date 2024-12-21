@extends('layouts.admin')

@section('content')
    <h1 class="mt-4">SuperAdmin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <div class="mt-2" style="font-size: 2.5rem; font-weight: bold; text-align: center;">
                        9
                        {{-- {{ $totalFaculties - 1 }} --}}
                    </div> <!-- Display total count prominently -->
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View All</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h5>Total Quiz Created</h5>
                    <div class="mt-2" style="font-size: 2.5rem; font-weight: bold; text-align: center;">
                        <!-- Replace with dynamic value -->
                        10
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View All</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5>Total Upload PYQ</h5>
                    <div class="mt-2" style="font-size: 2.5rem; font-weight: bold; text-align: center;">
                        <!-- Replace with dynamic value -->
                        25
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View All</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h5>No. of Study Materials</h5>
                    <div class="mt-2" style="font-size: 2.5rem; font-weight: bold; text-align: center;">
                        <!-- Replace with dynamic value -->
                        15
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View All</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
@endsection
