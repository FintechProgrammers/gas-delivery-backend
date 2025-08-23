@extends('layouts.user.app')

@push('scripts')
@endpush

@section('title', 'Dashboard')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">E-Learning Member</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Top Categories
                            </div>
                            <div>
                                <button type="button" class="btn btn-light btn-wave btn-sm">View All</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row gy-xl-0 gy-3">
                                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div>
                                        <a href="javascript:void(0);" class="category-link primary text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="category-svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"></path><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"></path></svg>
                                            <p class="fs-14 mb-1 text-default fw-semibold">Finance</p>
                                            <span class="fs-11 text-muted">1000+ Courses</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div>
                                        <a href="javascript:void(0);" class="category-link secondary text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="category-svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"></rect></g><g><g opacity=".3"><path d="M6.71,18.71c-0.28,0.28-2.17,0.76-2.17,0.76s0.47-1.88,0.76-2.17C5.47,17.11,5.72,17,6,17c0.55,0,1,0.45,1,1 C7,18.28,6.89,18.53,6.71,18.71z M7.41,10.83L5.5,10.01l1.97-1.97l1.44,0.29C8.34,9.16,7.83,10.03,7.41,10.83z M13.99,18.5 l-0.82-1.91c0.8-0.42,1.67-0.93,2.49-1.5l0.29,1.44L13.99,18.5z M19.99,4.01c0,0-3.55-0.69-8.23,3.99 c-1.32,1.32-2.4,3.38-2.73,4.04l2.93,2.93c0.65-0.32,2.71-1.4,4.04-2.73C20.68,7.56,19.99,4.01,19.99,4.01z M15,11 c-1.1,0-2-0.9-2-2c0-1.1,0.9-2,2-2s2,0.9,2,2C17,10.1,16.1,11,15,11z"></path></g><g><path d="M6,15c-0.83,0-1.58,0.34-2.12,0.88C2.7,17.06,2,22,2,22s4.94-0.7,6.12-1.88C8.66,19.58,9,18.83,9,18C9,16.34,7.66,15,6,15 z M6.71,18.71c-0.28,0.28-2.17,0.76-2.17,0.76s0.47-1.88,0.76-2.17C5.47,17.11,5.72,17,6,17c0.55,0,1,0.45,1,1 C7,18.28,6.89,18.53,6.71,18.71z M17.42,13.65L17.42,13.65c6.36-6.36,4.24-11.31,4.24-11.31s-4.95-2.12-11.31,4.24l-2.49-0.5 C7.21,5.95,6.53,6.16,6.05,6.63L2,10.69l5,2.14L11.17,17l2.14,5l4.05-4.05c0.47-0.47,0.68-1.15,0.55-1.81L17.42,13.65z M7.41,10.83L5.5,10.01l1.97-1.97l1.44,0.29C8.34,9.16,7.83,10.03,7.41,10.83z M13.99,18.5l-0.82-1.91 c0.8-0.42,1.67-0.93,2.49-1.5l0.29,1.44L13.99,18.5z M16,12.24c-1.32,1.32-3.38,2.4-4.04,2.73l-2.93-2.93 c0.32-0.65,1.4-2.71,2.73-4.04c4.68-4.68,8.23-3.99,8.23-3.99S20.68,7.56,16,12.24z M15,11c1.1,0,2-0.9,2-2s-0.9-2-2-2s-2,0.9-2,2 S13.9,11,15,11z"></path></g></g></svg>
                                            <p class="fs-14 mb-1 text-default fw-semibold">Digital Marketing</p>
                                            <span class="fs-11 text-muted">90+ Courses</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div>
                                        <a href="javascript:void(0);" class="category-link warning text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="category-svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="swatchbook"><path opacity="0.2" d="M9 22H5a3.003 3.003 0 0 1-3-3V5a3.003 3.003 0 0 1 3-3h4a3.003 3.003 0 0 1 3 3v14a3.003 3.003 0 0 1-3 3z"></path><path opacity="0.4" d="m20.293 6.535-2.828-2.828a3.004 3.004 0 0 0-4.243 0l-1.229 1.228c0 .022.007.043.007.065v14c0 .027-.007.052-.008.08l8.301-8.302a3.004 3.004 0 0 0 0-4.243z"></path><circle cx="7" cy="17" r="1" opacity="1"></circle><path opacity="1" d="m19.065 12.007-7.073 7.072c0-.027.008-.052.008-.079a3.003 3.003 0 0 1-3 3h10a3.003 3.003 0 0 0 3-3v-4a3 3 0 0 0-2.935-2.993z"></path></svg>
                                            <p class="fs-14 mb-1 text-default fw-semibold">Motivation</p>
                                            <span class="fs-11 text-muted">250+ Courses</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div>
                                        <a href="javascript:void(0);" class="category-link success">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="category-svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><rect fill="none" height="24" width="24"></rect><g opacity=".3"><path d="M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"></path></g><g><path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"></path></g></svg>
                                            <p class="fs-14 mb-1 text-default fw-semibold">Stocks &amp; Trading</p>
                                            <span class="fs-11 text-muted">100+ Courses</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="mb-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-semibold mb-0">Recommended Training / Courses</h6>
                <div>
                    <button class="btn btn-sm btn-primary-light btn-wave">View All</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="card custom-card">
                        <img src="https://www.deltadigital.pro/afl-resizer/product_images/1687310436-8652_crop_36--p--28_0--p--00_1198--p--61_719--p--17_0--p--00.jpeg?size=600,600&crop=smart" class="card-img-top" alt="...">
                        <div class="d-flex align-items-center justify-content-between nft-like-section w-100 px-3">
                            <div>
                                <p class="mb-0 text-fixed-white nft-auction-time">
                                    04hrs : 24m : 38s
                                </p>
                            </div>
                            <div>
                                <span class="badge nft-like-badge text-fixed-white"><i class="ri-heart-fill me-1 text-danger align-middle d-inline-block"></i>1.32k</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2 lh-1">
                                    <span class="avatar avatar-rounded avatar-md">
                                        <img src="{{ asset('assets/images/amb-success.webp') }}" alt="">
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Carlos Ardila</p>
                                    <p class="fs-12 text-muted mb-0">Finance</p>
                                </div>
                            </div>

                            <p class="fs-15 fw-semibold mb-2">INVERSIONISTA DELTA</p>
                            <div class="col-xxl-12 col-xl-12 col-lg-12 mb-4" >
                                <div>
                                    <p class="fw-semibold mb-2">Course status - 60% Completed</p>
                                    <div class="progress progress-xs progress-animate">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary-light btn-wave">Start Course</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="card custom-card">
                        <img src="https://www.deltadigital.pro/afl-resizer/product_images/1660601298-S7H5_crop_72--p--62_0--p--00_1800--p--00_1080--p--00_0--p--00.jpeg?size=600,600&crop=smart" class="card-img-top" alt="...">
                        <div class="d-flex align-items-center justify-content-between nft-like-section w-100 px-3">
                            <div>
                                <p class="mb-0 text-fixed-white nft-auction-time">
                                    04hrs : 24m : 38s
                                </p>
                            </div>
                            <div>
                                <span class="badge nft-like-badge text-fixed-white"><i class="ri-heart-fill me-1 text-danger align-middle d-inline-block"></i>1.32k</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2 lh-1">
                                    <span class="avatar avatar-rounded avatar-md">
                                        <img src="{{ asset('assets/images/amb-success.webp') }}" alt="">
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Carlos Ardila</p>
                                    <p class="fs-12 text-muted mb-0">Finance</p>
                                </div>
                            </div>

                            <p class="fs-15 fw-semibold mb-2">INVERSIONISTA DELTA</p>
                            <div class="col-xxl-12 col-xl-12 col-lg-12 mb-4" >
                                <div>
                                    <p class="fw-semibold mb-2">Course status - 60% Completed</p>
                                    <div class="progress progress-xs progress-animate">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary-light btn-wave">Start Course</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="card custom-card">
                        <img src="https://www.deltadigital.pro/afl-resizer/product_images/1651039945-NST4_crop_0--p--00_80--p--24_1200--p--00_720--p--00_0--p--00.jpeg?size=600,600&crop=smart" class="card-img-top" alt="...">
                        <div class="d-flex align-items-center justify-content-between nft-like-section w-100 px-3">
                            <div>
                                <p class="mb-0 text-fixed-white nft-auction-time">
                                    04hrs : 24m : 38s
                                </p>
                            </div>
                            <div>
                                <span class="badge nft-like-badge text-fixed-white"><i class="ri-heart-fill me-1 text-danger align-middle d-inline-block"></i>1.32k</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2 lh-1">
                                    <span class="avatar avatar-rounded avatar-md">
                                        <img src="{{ asset('assets/images/amb-success.webp') }}" alt="">
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Carlos Ardila</p>
                                    <p class="fs-12 text-muted mb-0">Finance</p>
                                </div>
                            </div>

                            <p class="fs-15 fw-semibold mb-2">INVERSIONISTA DELTA</p>
                            <div class="col-xxl-12 col-xl-12 col-lg-12 mb-4" >
                                <div>
                                    <p class="fw-semibold mb-2">Course status - 60% Completed</p>
                                    <div class="progress progress-xs progress-animate">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary-light btn-wave">Start Course</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="card custom-card">
                        <img src="https://www.deltadigital.pro/afl-resizer/product_images/1651039945-NST4_crop_0--p--00_80--p--24_1200--p--00_720--p--00_0--p--00.jpeg?size=600,600&crop=smart" class="card-img-top" alt="...">
                        <div class="d-flex align-items-center justify-content-between nft-like-section w-100 px-3">
                            <div>
                                <p class="mb-0 text-fixed-white nft-auction-time">
                                    04hrs : 24m : 38s
                                </p>
                            </div>
                            <div>
                                <span class="badge nft-like-badge text-fixed-white"><i class="ri-heart-fill me-1 text-danger align-middle d-inline-block"></i>1.32k</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2 lh-1">
                                    <span class="avatar avatar-rounded avatar-md">
                                        <img src="{{ asset('assets/images/amb-success.webp') }}" alt="">
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Carlos Ardila</p>
                                    <p class="fs-12 text-muted mb-0">Finance</p>
                                </div>
                            </div>

                            <p class="fs-15 fw-semibold mb-2">INVERSIONISTA DELTA</p>
                            <div class="col-xxl-12 col-xl-12 col-lg-12 mb-4" >
                                <div>
                                    <p class="fw-semibold mb-2">Course status - 60% Completed</p>
                                    <div class="progress progress-xs progress-animate">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary-light btn-wave">Start Course</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
@push('scripts')

@endpush
