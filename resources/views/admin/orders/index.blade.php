@extends('layouts.app')

@section('title', 'Orders')

@push('styles')
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Orders</p>
        </div>
    </div>
    <div class="card custom-card">
        <div class="p-3">
            <div class="row align-items-end ">
                <div class="col-lg-3 mb-lg-0 mb-3">
                    <label for="searchInputSearch">Search</label>
                    <input type="search" class="form-control" placeholder="Search by reference" id="search"
                        aria-describedby="emailHelp">
                </div>
                <div class="col-lg-3 mb-lg-0 mb-3">
                    <label for="searchInputSearch">Status</label>
                    <select class="form-control" id="status">
                        <option value="">--select--</option>
                        @foreach (\App\Models\GasOrder::STATUS as $item)
                            <option value="{{ $item }}">
                                <span class="text-capitalize">{{ $item }}</span>
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-2">
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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap mb-0">
                    <tbody class="active-tab" id="table-body">

                    </tbody>
                </table>
            </div>
            <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Datatables Cdn -->
    @include('admin.orders.scritps._load-table')
    @include('admin.orders.scritps._actions')
@endpush
