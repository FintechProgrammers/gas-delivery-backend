@extends('layouts.user.app')

@section('title','Tickets')

@push('styles')
@endpush

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Support Tickets</p>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-wave">
                <i class="las la-user-plus me-2 align-middle d-inline-block"></i>Create Ticket
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        {{-- id="scroll-vertical" --}}
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>Date</th>
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
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
@endsection
@push('scripts')
    @include('user.support.scripts._load-table')
    @include('user.support.scripts._submit-form')
    @include('user.support.scripts._actions')
@endpush
