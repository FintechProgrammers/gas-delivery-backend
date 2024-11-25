@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Customers</p>
                            <h3 class="mt-2 mb-0 fw-bold">24k</h3>
                        </div>
                        <!--end col-->
                        <div class="col-3 align-self-center">
                            <div
                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                <i class="iconoir-hexagon-dice h1 align-self-center mb-0 text-secondary"></i>
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
        <!--end col-->
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Riders</p>
                            <h3 class="mt-2 mb-0 fw-bold">88k</h3>
                        </div>
                        <!--end col-->
                        <div class="col-3 align-self-center">
                            <div
                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                <i class="iconoir-clock h1 align-self-center mb-0 text-secondary"></i>
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
        <!--end col-->
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Vendros</p>
                            <h3 class="mt-2 mb-0 fw-bold">45k</h3>
                        </div>
                        <!--end col-->
                        <div class="col-3 align-self-center">
                            <div
                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                <i class="iconoir-percentage-circle h1 align-self-center mb-0 text-secondary"></i>
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
        <!--end col-->
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Revenue Overview</h4>
                        </div>
                        <!--end col-->
                        <div class="col-auto">
                            <div class="dropdown">
                                <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="icofont-calendar fs-5 me-1"></i>
                                    This Year<i class="las la-angle-down ms-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">This Year</a>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-header-->
                <div class="card-body pt-0">
                    <div id="audience_overview" class="apex-charts"></div>
                </div>
                <!--end card-body-->
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
                        </div><!--end col-->
                        <div class="col-auto">
                            <div class="dropdown">
                                <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="icofont-calendar fs-5 me-1"></i> This Year<i
                                        class="las la-angle-down ms-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">This Year</a>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div id="customers-line" class="apex-charts"></div>
                </div><!--end card-body-->
            </div>
        </div>
        <!--end col-->
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    @include('admin.dashboard.scripts._analytics')
    <script>
        var chart_customer = {
                series: [{
                        name: "New Customers ",
                        data: [0, 20, 15, 19, 14, 25, 30]
                    },
                    {
                        name: "Returning Customers",
                        data: [0, 8, 7, 13, 26, 16, 25]
                    },
                ],
                chart: {
                    fontFamily: "inherit",
                    height: 233,
                    type: "line",
                    toolbar: {
                        show: !1
                    },
                    sparkline: {
                        enabled: !0
                    },
                },
                colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                grid: {
                    show: !0,
                    strokeDashArray: 3
                },
                stroke: {
                    curve: "smooth",
                    colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                    width: 2,
                },
                markers: {
                    colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                    strokeColors: "transparent",
                },
                tooltip: {
                    x: {
                        show: !1
                    },
                    followCursor: !0
                },
            },
            chart_line = new ApexCharts(
                document.querySelector("#customers-line"),
                chart_customer
            );
        chart_line.render();
    </script>
@endpush
