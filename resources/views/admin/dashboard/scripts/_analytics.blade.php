<script>
    document.addEventListener('DOMContentLoaded', function() {
        let areaChart = null;

        // Function to fetch data and update charts using AJAX
        function fetchChartData(period) {
            $.ajax({
                url: '{{ route('admin.dashboard.stats.revenue') }}?period=' + encodeURIComponent(
                    period),
                type: 'GET',
                dataType: 'json',
                success: function(data) {

                    // Update area chart
                    const areaOptions = {
                        series: [{
                                name: "Income",
                                data: data.monthDataSeries1.prices
                            },
                            {
                                name: "Expenses",
                                data: data.monthDataSeries2.prices
                            }
                        ],
                        chart: {
                            height: 280,
                            type: "area",
                            toolbar: {
                                show: false
                            },
                            dropShadow: {
                                enabled: true,
                                top: 12,
                                left: 0,
                                bottom: 0,
                                right: 0,
                                blur: 2,
                                color: "rgba(132, 145, 183, 0.3)",
                                opacity: 0.35
                            }
                        },
                        annotations: {
                            xaxis: [{
                                x: 312,
                                strokeDashArray: 4,
                                borderWidth: 1,
                                borderColor: ["var(--bs-secondary)"]
                            }],
                            points: [{
                                x: 312,
                                y: 52,
                                marker: {
                                    size: 6,
                                    fillColor: ["var(--bs-primary)"],
                                    strokeColor: ["var(--bs-card-bg)"],
                                    strokeWidth: 4,
                                    radius: 5
                                },
                                label: {
                                    borderWidth: 1,
                                    offsetY: -110,
                                    text: "50k",
                                    style: {
                                        background: ["var(--bs-primary)"],
                                        fontSize: "14px",
                                        fontWeight: "600"
                                    }
                                }
                            }]
                        },
                        colors: ["#22c55e", "rgba(106, 155, 155, 0.3)"],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            curve: "smooth",
                            width: [3, 3],
                            dashArray: [0, 0],
                            lineCap: "round"
                        },
                        labels: data.monthDataSeries1.dates,
                        yaxis: {
                            labels: {
                                offsetX: -12,
                                offsetY: 0,
                                formatter: function(value) {
                                    return "₦" + value.toLocaleString();
                                }
                            }
                        },
                        grid: {
                            strokeDashArray: 3,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            },
                            yaxis: {
                                lines: {
                                    show: false
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                type: "vertical",
                                shadeIntensity: 1,
                                inverseColors: false,
                                opacityFrom: 0.05,
                                opacityTo: 0.05,
                                stops: [45, 100]
                            }
                        }
                    };

                    // Destroy existing chart before creating new one
                    if (areaChart !== null) {
                        areaChart.destroy();
                    }

                    // Render new chart
                    areaChart = new ApexCharts(document.querySelector("#audience_overview"),
                        areaOptions);
                    areaChart.render();
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching chart data:', error);
                }
            });
        }

        // Initial load with default period
        fetchChartData('This Year');

        // Handle dropdown clicks
        document.querySelectorAll('.rev').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.getAttribute('data-period');
                document.getElementById('rev-period-label').textContent = period;
                fetchChartData(period);
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let customersChart = null;

        // Function to fetch data and update chart using AJAX
        function fetchChartData(period) {
            $.ajax({
                url: '{{ route('admin.dashboard.stats.customers') }}?period=' + encodeURIComponent(
                    period),
                type: 'GET',
                dataType: 'json',
                success: function(data) {

                    // Update line chart
                    const chartOptions = {
                        series: data.series,
                        chart: {
                            fontFamily: "inherit",
                            height: 233,
                            type: "line",
                            toolbar: {
                                show: false
                            },
                            sparkline: {
                                enabled: true
                            }
                        },
                        colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                        grid: {
                            show: true,
                            strokeDashArray: 3
                        },
                        stroke: {
                            curve: "smooth",
                            colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                            width: 2
                        },
                        markers: {
                            colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                            strokeColors: "transparent"
                        },
                        xaxis: {
                            categories: data.categories,
                            labels: {
                                style: {
                                    colors: "rgb(107, 114, 128)",
                                    fontSize: "12px"
                                }
                            }
                        },
                        tooltip: {
                            x: {
                                show: false
                            },
                            followCursor: true
                        }
                    };

                    // Destroy existing chart before creating new one
                    if (customersChart !== null) {
                        customersChart.destroy();
                    }

                    // Render new chart
                    customersChart = new ApexCharts(document.querySelector("#customers-line"),
                        chartOptions);
                    customersChart.render();
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching chart data:', error);
                }
            });
        }

        // Initial load with default period
        fetchChartData('This Year');

        // Handle dropdown clicks
        document.querySelectorAll('.cus').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.getAttribute('data-period');
                document.getElementById('period-label').textContent = period;
                fetchChartData(period);
            });
        });
    });
</script>
