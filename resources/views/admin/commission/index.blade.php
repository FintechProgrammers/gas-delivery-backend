@extends('layouts.app')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Commission Plan</p>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <button data-url="{{ route('admin.commission.create') }}" type="button" class="btn btn-primary btn-wave trigerCanvas"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <i class="las la-user-plus me-2 align-middle d-inline-block"></i>Create Plan
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered text-nowrap w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Percentage</th>
                        <th width="10">Action</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
        </div>
    </div>
    @include('partials._off-canvas')
@endsection
@push('scripts')
    @include('admin.commission.scripts._load-table')
    @include('admin.commission.scripts._form-script')
    @include('admin.commission.scripts._actions')
@endpush
