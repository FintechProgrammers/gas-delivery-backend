@extends('layouts.app')

@section('title', 'Tranasctions')

@push('styles')
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Transactions</p>
        </div>
    </div>
    <div class="card custom-card">
        <div class="p-3">
            <div class="row align-items-end ">
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Search</label>
                    <input type="search" class="form-control" placeholder="Search by email,name and account type"
                        id="search" aria-describedby="emailHelp">
                </div>
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Status</label>
                    <select class="form-control" id="status">
                        <option value="">--select--</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Type</label>
                    <select class="form-control" id="type">
                        <option value="">--select--</option>
                        <option value="credit">Credit</option>
                        <option value="debit">Debit</option>
                    </select>
                </div>
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Action</label>
                    <select class="form-control" id="action">
                        <option value="">--select--</option>
                        <option value="deposit">Deposit</option>
                        <option value="withdrawal">Withdrawal</option>
                        <option value="purchase">Purchase</option>
                        <option value="reversal">Reversal</option>
                    </select>
                </div>
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <label for="searchInputSearch">Date</label>
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
        <hr />
        <div class="card-body">
            <table class="table table-bordered text-nowrap w-100">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Date</th>
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
    @include('admin.transaction.scripts._load-table')
@endpush