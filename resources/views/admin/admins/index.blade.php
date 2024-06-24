@extends('layouts.app')


@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Staff Management</p>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <button type="button" class="btn btn-primary btn-wave trigerModal" data-url="{{ route('admin.admins.create') }}"
                data-bs-toggle="modal" data-bs-target="#primaryModal">
                <i class="las la-user-plus me-2 align-middle d-inline-block"></i>{{ __('Add Staff') }}
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="search" class="form-control" placeholder="Search by name, email">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        {{-- id="scroll-vertical" --}}
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Roles</th>
                                <th>Date Join</th>
                                <th>Status</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    @include('admin.admins.scripts._load_table')
    @include('admin.admins.scripts._submit_form')
    @include('admin.admins.scripts._actions')
@endpush
