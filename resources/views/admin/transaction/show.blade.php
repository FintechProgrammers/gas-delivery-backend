@extends('layouts.app')

@section('title', 'Transaction Details')

@push('styles')
@endpush

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
                <i class="iconoir-arrow-left me-1"></i> Back to Transactions
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">

            @include('admin.transaction._transaction-summary')

            @include('admin.transaction._transaction-details')

        </div> <!-- end col -->
        <div class="col-lg-4">
            @include('admin.transaction._user-information')

            @if($transaction->provider_id)
                @include('admin.transaction._provider-information')
            @endif
        </div> <!-- end col -->
    </div>
@endsection
@push('scripts')
@endpush
