@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">User Management</p>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <button type="button" class="btn btn-primary btn-wave trigerModal" data-url="{{ route('admin.users.create') }}"
                data-bs-toggle="modal" data-bs-target="#primaryModal">
                <i class="las la-user-plus me-2 align-middle d-inline-block"></i>Add User
            </button>
        </div>
    </div>
    <div class="card custom-card">
        <div class="p-3">
            <div class="row align-items-end ">
                <div class="col-lg-2 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Search</label>
                        <input type="search" class="form-control" placeholder="Search by email,name and account type"
                            id="search" aria-describedby="emailHelp">
                </div>
                <div class="col-lg-2 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Account Type</label>
                    <select class="form-control" id="account_type">
                        <option value="">--select--</option>
                        <option value="customer">Customer</option>
                        <option value="ambassador">Ambassador</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Status</label>
                    <select class="form-control" id="status">
                        <option value="">--select--</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Date Joined</label>
                    <input type="text" name="datepicker" id="search-date" class="form-control" value="" />
                </div>
                <div class="col-lg-2 mb-lg-0">
                    <button id="filter"
                        class="btn btn-size btn-primary btn-hover-effect-1 rounded-pill make-text-bold w-100">Filter</button>
                </div>
                <div class="col-lg-2 mb-lg-0">
                    <button id="reset"
                        class="btn btn-size btn-outline-dark btn-hover-effect-1 rounded-pill make-text-bold w-100">Reset</button>
                </div>
            </div>
        </div>
        <hr/>
        <div class="card-body">
            <table  class="table table-bordered text-nowrap w-100">
                {{-- id="scroll-vertical" --}}
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th class="text-center">Account Type</th>
                        <th>Date Join</th>
                        <th>Status</th>
                        <th width="10">Action</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
        </div>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    </div>
@endsection
@push('scripts')
    <!-- Datatables Cdn -->

    @include('admin.users.scritps._load-table')
    @include('admin.users.scritps._submit-form')
    @include('admin.users.scritps._user_actions')

@endpush
