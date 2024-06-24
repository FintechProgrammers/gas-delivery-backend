@extends('layouts.app')

@push('styles')

@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Sales</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Service Name</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date</th>
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
    @include('admin.sales.scritps._load-table')
    @include('admin.sales.scritps._submit-form')
@endpush
