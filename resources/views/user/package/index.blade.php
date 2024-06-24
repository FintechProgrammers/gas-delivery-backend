@extends('layouts.user.app')

@section('title', 'Package')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Packages</p>
        </div>
    </div>
    <div class="row">
        @forelse ($services as $item)
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card custom-card product-card">
                    <div class="card-body">
                        <a href="{{ route('package.details',$item->uuid) }}" class="product-image">
                            <img src="{{ $item->image }}" class="card-img mb-3" alt="...">
                        </a>
                        {{-- <div class="product-icons">
                            <a href="#" class="cart"><i class="ri-shopping-cart-line"></i></a>
                            <a href="#" class="view">
                                <i class="ri-eye-line"></i>
                            </a>
                        </div> --}}
                        <p class="product-name fw-semibold mb-0 d-flex align-items-center justify-content-between">
                            {{ $item->name }}</p>
                        <p class="product-description fs-11 text-muted mb-2">{{ limitWords($item->description) }}</p>
                        <p class="mb-1 fw-semibold fs-16 d-flex align-items-center justify-content-between">
                            <span>${{ $item->price }} for <span
                                    class="text-muted ms-1 d-inline-block op-6">{{ convertDaysToUnit($item->duration, $item->duration_unit) . ' ' . $item->duration_unit }}</span></span>
                        <p class="fs-11 text-success fw-semibold mb-3 d-flex align-items-center">
                            <i class="ti ti-discount-2 fs-16 me-1"></i>
                            @if ($item->serviceProduct->isNotEmpty())
                                {{ $item->serviceProduct->pluck('product.name')->implode(', ') }}
                            @else
                                No products available.
                            @endif
                        </p>
                        <a class="btn btn-primary btn-sm" href="{{ route('package.details',$item->uuid) }}">{{ __('Purchase') }}</a>
                    </div>
                </div>
            </div>
        @empty
        <div class="col-lg-12">
            <x-no-datacomponent title="no package available" />
        </div>
        @endforelse

    </div>
@endsection
