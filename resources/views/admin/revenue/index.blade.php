@extends('layouts.app')

@section('title', 'Riders')

@push('styles')
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Revenue Management</p>
        </div>
    </div>
    <div class="card custom-card">
        <div class="p-3">
            <div class="row align-items-end ">

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
        <hr />
        <div class="card-body">
            <table class="table table-bordered text-nowrap w-100">
                {{-- id="scroll-vertical" --}}
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Amount</th>
                        <th>Vendor Fee</th>
                        <th>Rider Fee</th>
                        <th>Referral Fee</th>
                        <th>Date Join</th>
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
    @include('admin.revenue.scripts._load-table')
@endpush
