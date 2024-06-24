@extends('layouts.app')

@push('styles')

@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Withdrawals</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">status</th>
                                    <th scope="col" width="30%">Date</th>
                                    <th scope="col"></th>
                                </tr>
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
    @include('admin.withdrawals.scritps._load-table')
@endpush
