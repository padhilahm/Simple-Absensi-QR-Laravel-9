{{-- @dd($classess[0]->students[2]->attendances) --}}
{{-- @dd($attendances) --}}

@extends('template.main')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            @foreach ($classess as $class)
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">{{ $class->name }}</h5>
                                <small class="text-muted">{{ date('d M Y') }}</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <h2 class="mb-2">{{ $class->students()->count() }}</h2>
                                    <span>Jumlah Siswa</span>
                                </div>
                                <div id="orderStatisticsChart-{{ $class->id }}"></div>
                            </div>
                            <ul class="p-0 m-0">
                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($class->students as $student)
                                    <li class="d-flex mb-4 pb-1">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class="bx bx-user"></i></span>
                                        </div>
                                        <div
                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <h6 class="mb-0">{{ $student->name }}</h6>
                                                <small class="text-muted">{{ $student->student_id_number }}</small>
                                            </div>
                                            <div class="user-progress">
                                                @if ($student->attendances->where('date', date('Y-m-d'))->count() > 0)
                                                    <span class="badge bg-success">Hadir</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak</span>
                                                @endif
                                                {{-- <small class="fw-semibold">82.5k</small> --}}
                                            </div>
                                        </div>
                                    </li>
                                    @php
                                        $i++;
                                        if ($i > 5) {
                                            break;
                                        }
                                    @endphp
                                @endforeach
                            </ul>
                            <div class="text-center">
                                <a href="/dashboard/attendance/{{ $class->id }}/{{ date('Y-m-d') }}"
                                    class="btn btn-sm btn-primary">Lihat Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Order Statistics -->
            @endforeach

        </div>
    </div>
    <!-- / Content -->
@endsection

@section('script')
    @foreach ($classess as $class)
        @php
            $present = $class
                ->students()
                ->whereHas('attendances', function ($query) {
                    $query->where('date', date('Y-m-d'));
                })
                ->count();
            $absent = $class
                ->students()
                ->whereDoesntHave('attendances', function ($query) {
                    $query->where('date', date('Y-m-d'));
                })
                ->count();
        @endphp
        <script>
            let cardColor_{{ $class->id }}, headingColor_{{ $class->id }}, axisColor_{{ $class->id }},
                shadeColor_{{ $class->id }}, borderColor_{{ $class->id }};

            cardColor_{{ $class->id }} = config.colors.white;
            headingColor_{{ $class->id }} = config.colors.headingColor_{{ $class->id }};
            axisColor_{{ $class->id }} = config.colors.axisColor_{{ $class->id }};
            borderColor_{{ $class->id }} = config.colors.borderColor_{{ $class->id }};

            // Order Statistics Chart
            // --------------------------------------------------------------------
            const chartOrderStatistics_{{ $class->id }} = document.querySelector(
                    "#orderStatisticsChart-{{ $class->id }}"
                ),
                orderChartConfig_{{ $class->id }} = {
                    chart: {
                        height: 165,
                        width: 130,
                        type: "donut",
                    },
                    labels: ["Hadir", "Tidak Hadir"],
                    series: [
                        {{ $present }},
                        {{ $absent }},
                    ],
                    colors: [
                        config.colors.success,
                        config.colors.danger,
                    ],
                    stroke: {
                        width: 5,
                        colors: cardColor_{{ $class->id }},
                    },
                    dataLabels: {
                        enabled: false,
                        formatter: function(val, opt) {
                            return parseInt(val) + "%";
                        },
                    },
                    legend: {
                        show: false,
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            right: 15,
                        },
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: "75%",
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: "1.5rem",
                                        fontFamily: "Public Sans",
                                        color: headingColor_{{ $class->id }},
                                        offsetY: -15,
                                        formatter: function(val) {
                                            return parseInt(val);
                                        },
                                    },
                                    name: {
                                        offsetY: 20,
                                        fontFamily: "Public Sans",
                                    },
                                    total: {
                                        show: true,
                                        fontSize: "0.8125rem",
                                        color: axisColor_{{ $class->id }},
                                        label: "Kehadiran",
                                        formatter: function(w) {
                                            return Math.round(({{ $present }} / ({{ $present }} +
                                                    {{ $absent }})) *
                                                100) + '%';
                                        },
                                    },
                                },
                            },
                        },
                    },
                };
            if (
                typeof chartOrderStatistics_{{ $class->id }} !== undefined &&
                chartOrderStatistics_{{ $class->id }} !== null
            ) {
                const statisticsChart_{{ $class->id }} = new ApexCharts(
                    chartOrderStatistics_{{ $class->id }},
                    orderChartConfig_{{ $class->id }}
                );
                statisticsChart_{{ $class->id }}.render();
            }
        </script>
    @endforeach
@endsection
