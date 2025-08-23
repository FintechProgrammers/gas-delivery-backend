@extends('layouts.user.app')

@push('scripts')
@endpush

@section('title', 'Dashboard')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Welcome back, {{ ucfirst(auth()->user()->name) }}!</p>
            <span class="fs-semibold text-muted">Track your sales activity, leads and deals here.</span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            @include('user.dashboard._profile-card')
            @if (Auth::user()->is_ambassador)
                @include('user.dashboard._activities')
            @endif
        </div>
        <div class="col-lg-9">

        </div>
    </div>
    @include('profile.partials._profile-modal')
@endsection
@push('scripts')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper.js') }}"></script>
    @if (Auth::user()->profile_completion_percentage < 100)
        <script>
            showProfileModal()
        </script>
    @endif
    <script>
        function showProfileModal() {
            var myModal = new bootstrap.Modal(document.getElementById('profileUpdateModal'), {
                keyboard: false
            });
            myModal.show();
        }

        var myElement1 = document.getElementById('recent-activity');
        new SimpleBar(myElement1, {
            autoHide: true
        });
    </script>
    {{-- <script>
        /* Revenue Analytics Chart */
        var options = {
            series: [{
                    type: 'line',
                    name: 'Profit',
                    data: [{
                            x: 'Jan',
                            y: 100
                        },
                        {
                            x: 'Feb',
                            y: 210
                        },
                        {
                            x: 'Mar',
                            y: 180
                        },
                        {
                            x: 'Apr',
                            y: 454
                        },
                        {
                            x: 'May',
                            y: 230
                        },
                        {
                            x: 'Jun',
                            y: 320
                        },
                        {
                            x: 'Jul',
                            y: 656
                        },
                        {
                            x: 'Aug',
                            y: 830
                        },
                        {
                            x: 'Sep',
                            y: 350
                        },
                        {
                            x: 'Oct',
                            y: 350
                        },
                        {
                            x: 'Nov',
                            y: 210
                        },
                        {
                            x: 'Dec',
                            y: 410
                        }
                    ]
                },
                {
                    type: 'line',
                    name: 'Revenue',
                    chart: {
                        dropShadow: {
                            enabled: true,
                            enabledOnSeries: undefined,
                            top: 5,
                            left: 0,
                            blur: 3,
                            color: '#000',
                            opacity: 0.1
                        }
                    },
                    data: [{
                            x: 'Jan',
                            y: 180
                        },
                        {
                            x: 'Feb',
                            y: 620
                        },
                        {
                            x: 'Mar',
                            y: 476
                        },
                        {
                            x: 'Apr',
                            y: 220
                        },
                        {
                            x: 'May',
                            y: 520
                        },
                        {
                            x: 'Jun',
                            y: 780
                        },
                        {
                            x: 'Jul',
                            y: 435
                        },
                        {
                            x: 'Aug',
                            y: 515
                        },
                        {
                            x: 'Sep',
                            y: 738
                        },
                        {
                            x: 'Oct',
                            y: 454
                        },
                        {
                            x: 'Nov',
                            y: 525
                        },
                        {
                            x: 'Dec',
                            y: 230
                        }
                    ]
                },
                {
                    type: 'area',
                    name: 'Sales',
                    chart: {
                        dropShadow: {
                            enabled: true,
                            enabledOnSeries: undefined,
                            top: 5,
                            left: 0,
                            blur: 3,
                            color: '#000',
                            opacity: 0.1
                        }
                    },
                    data: [{
                            x: 'Jan',
                            y: 200
                        },
                        {
                            x: 'Feb',
                            y: 530
                        },
                        {
                            x: 'Mar',
                            y: 110
                        },
                        {
                            x: 'Apr',
                            y: 130
                        },
                        {
                            x: 'May',
                            y: 480
                        },
                        {
                            x: 'Jun',
                            y: 520
                        },
                        {
                            x: 'Jul',
                            y: 780
                        },
                        {
                            x: 'Aug',
                            y: 435
                        },
                        {
                            x: 'Sep',
                            y: 475
                        },
                        {
                            x: 'Oct',
                            y: 738
                        },
                        {
                            x: 'Nov',
                            y: 454
                        },
                        {
                            x: 'Dec',
                            y: 480
                        }
                    ]
                }
            ],
            chart: {
                height: 350,
                animations: {
                    speed: 500
                },
                dropShadow: {
                    enabled: true,
                    enabledOnSeries: undefined,
                    top: 8,
                    left: 0,
                    blur: 3,
                    color: '#000',
                    opacity: 0.1
                },
            },
            colors: ["rgb(132, 90, 223)", "rgba(35, 183, 229, 0.85)", "rgba(119, 119, 142, 0.05)"],
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 3
            },
            stroke: {
                curve: 'smooth',
                width: [2, 2, 0],
                dashArray: [0, 5, 0],
            },
            xaxis: {
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return "$" + value;
                    }
                },
            },
            tooltip: {
                y: [{
                    formatter: function(e) {
                        return void 0 !== e ? "$" + e.toFixed(0) : e
                    }
                }, {
                    formatter: function(e) {
                        return void 0 !== e ? "$" + e.toFixed(0) : e
                    }
                }, {
                    formatter: function(e) {
                        return void 0 !== e ? e.toFixed(0) : e
                    }
                }]
            },
            legend: {
                show: true,
                customLegendItems: ['Profit', 'Revenue', 'Sales'],
                inverseOrder: true
            },
            title: {
                text: 'Revenue Analytics with sales & profit (USD)',
                align: 'left',
                style: {
                    fontSize: '.8125rem',
                    fontWeight: 'semibold',
                    color: '#8c9097'
                },
            },
            markers: {
                hover: {
                    sizeOffset: 5
                }
            }
        };
        document.getElementById('crm-revenue-analytics').innerHTML = '';
        var chart = new ApexCharts(document.querySelector("#crm-revenue-analytics"), options);
        chart.render();

        function revenueAnalytics() {
            chart.updateOptions({
                colors: ["rgba(" + myVarVal + ", 1)", "rgba(35, 183, 229, 0.85)", "rgba(119, 119, 142, 0.05)"],
            });
        }
        /* Revenue Analytics Chart */
    </script> --}}
    <script>
        Chart.defaults.borderColor = "rgba(142, 156, 173,0.1)", Chart.defaults.color = "#8c9097";
        const labels = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
        ];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Sales Analytics',
                backgroundColor: 'rgb(132, 90, 223)',
                borderColor: 'rgb(132, 90, 223)',
                data: [0, 10, 5, 2, 20, 30, 45],
            }]
        };
        const config = {
            type: 'line',
            data: data,
            options: {}
        };
        const myChart = new Chart(
            document.getElementById('chartjs-line'),
            config
        );
    </script>
    @include('user.dashboard.scripts._process_ambassedor_payment')
@endpush
