@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row justify-content-center">
        @foreach ($stats as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">{{ $item->title }}</p>
                                <h3 class="mt-2 mb-0 fw-bold">{{ $item->value }}</h3>
                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="{{ $item->icon }} h1 align-self-center mb-0 text-secondary"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
        @endforeach
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Revenue Overview</h4>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown">
                                <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="icofont-calendar fs-5 me-1"></i>
                                    <span id="rev-period-label">This Year</span><i class="las la-angle-down ms-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item rev" href="#" data-period="Today">Today</a>
                                    <a class="dropdown-item rev" href="#" data-period="Last Week">Last Week</a>
                                    <a class="dropdown-item rev" href="#" data-period="Last Month">Last Month</a>
                                    <a class="dropdown-item rev" href="#" data-period="This Year">This Year</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item rev" href="#" data-period="All Time">All Time</a>
                                    @if(!empty($availableYears))
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header">Previous Years</h6>
                                        @foreach($availableYears as $year)
                                            @if($year != date('Y'))
                                                <a class="dropdown-item rev" href="#" data-period="{{ $year }}">{{ $year }}</a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="audience_overview" class="apex-charts"></div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Customers Growth</h4>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown">
                                <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="icofont-calendar fs-5 me-1"></i>
                                    <span id="period-label">This Year</span><i class="las la-angle-down ms-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item cus" href="#" data-period="Today">Today</a>
                                    <a class="dropdown-item cus" href="#" data-period="Last Week">Last Week</a>
                                    <a class="dropdown-item cus" href="#" data-period="Last Month">Last Month</a>
                                    <a class="dropdown-item cus" href="#" data-period="This Year">This Year</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item cus" href="#" data-period="All Time">All Time</a>
                                    @if(!empty($availableYears))
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header">Previous Years</h6>
                                        @foreach($availableYears as $year)
                                            @if($year != date('Y'))
                                                <a class="dropdown-item cus" href="#" data-period="{{ $year }}">{{ $year }}</a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="customers-line" class="apex-charts"></div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    @include('admin.dashboard.scripts._analytics')
@endpush
