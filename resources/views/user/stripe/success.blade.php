@extends('layouts.user.app')

@section('title', 'Stripe Success')

@section('content')
    <div class="p-5 checkout-payment-success my-3">

        <div class="mb-5">
            <h5 class="text-success fw-semibold">{{ $title }}</h5>
        </div>
        @if (isset($image))
            <div class="me-2">
                <span class="avatar avatar-lg avatar-rounded">
                    <img src="{{ $image }}" alt="">
                </span>
            </div>
            {{-- <div class="mb-5">
                        <img src="{{ $image }}" alt="" class="img-fluid">
                    </div> --}}
        @endif

        <div class="mb-4 d-flex flex-column align-items-center">
            <p class="mb-1 fs-14 w-25">
                {{ $message }}
            </p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-success">Continue to Dashboard</a>
    </div>
@endsection
