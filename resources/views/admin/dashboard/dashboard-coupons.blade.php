
@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Dashboard Coupons')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/extensions/ext-component-toastr.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/css/base/pages/app-invoice-list.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin/css/base/plugins/forms/pickers/form-flat-pickr.css') }}">

@endsection

@section('content')
    <!-- Dashboard Analytics Start -->
    <section id="dashboard-analytics">
        <div class="row match-height">
            <!-- Earnings Card -->
            {{-- <div class="col-lg-4 col-md-6 col-6">
                <div class="card earnings-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="card-title mb-1">Earnings</h4>
                                <div class="font-small-2">This Month</div>
                                <h5 class="mb-1">{{$couponsusedThisMonthcCount}}</h5>
                                <p class="card-text text-muted font-small-2">
                                    <span class="fw-bolder">68.2%</span><span> more earnings than last month.</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div id="earnings-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!--/ Earnings Card -->
            {{--            --}}
            <!-- Statistics Card -->
            <div class="col-md-6 col-6">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Statistics Coupon</h4>

                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <i data-feather="trending-up" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{$coupons}}</h4>
                                        <p class="card-text font-small-3 mb-0">Total</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0" title="{{$TotalPrice}}">{{getAmount($TotalPrice)}}</h4>
                                        <p class="card-text font-small-3 mb-0">Price</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-danger me-2">
                                        <div class="avatar-content">
                                            <i data-feather="box" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{$couponsused}}</h4>
                                        <p class="card-text font-small-3 mb-0">Used</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-success me-2">
                                        <div class="avatar-content">
                                            <i data-feather="dollar-sign" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0" title="{{$couponsusedTotalPrice}}">{{getAmount($couponsusedTotalPrice)}}</h4>
                                        <p class="card-text font-small-3 mb-0">Earnings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-6">
                <div class="card">
                    <div
                        class="
                            card-header
                            d-flex
                            justify-content-between
                            align-items-sm-center align-items-start
                            flex-sm-row flex-column
                        "
                    >
                        <div class="header-left">
                            <h4 class="card-title">Latest Statistics Coupon</h4>
                        </div>
                        <div class="header-right d-flex align-items-center mt-sm-0 mt-1">
                            <i data-feather="calendar"></i>
                            <input
                                type="text"
                                class="form-control flat-picker border-0 shadow-none bg-transparent pe-0"
                                placeholder="YYYY-MM-DD"
                                name="date"
                                id="filterDate"
                            />
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas class="bar-chart-ex chartjs" data-count="{{$chartDataByDayCount}}" data-date="{{$chartDataByDayDate}}" data-height="400"></canvas>
                    </div>
                </div>
            </div>
            <!--/ Statistics Card -->


        </div>



        <!-- List DataTable -->
        <div class="row">
            <div class="col-12">
                <div class="card invoice-list-wrapper">
                    <div class="card-body border-bottom">
                        <h4 class="card-title">Search & Filter</h4>
                        <div class="row">
                            <div class="col-md-4 user_role">
                                <label class="form-label" for="UserRole">Grade</label>
                                <select id="grade_id_filter" class="form-select text-capitalize mb-md-0 mb-2">
                                    <option value=""> Select Grade </option>
                                    @foreach ( $grades as $grade )
                                        <option value=" {{$grade->id}} ">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 user_plan">

                                <label class="form-label" for="UserRole">Used</label>
                                <select id="usedCoupon" class="form-select text-capitalize mb-md-0 mb-2">
                                    <option value=""> All </option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-datatable table-responsive">
                        <table class="coupon-list-table table">
                            <thead>
                            <tr>

                                <th>#</th>
                                <th>Code</th>
                                <th>Price</th>
                                <th class="text-truncate">Create Date</th>
                                <th>Use Date</th>
                                <th>Student</th>
                                <th>Usage</th>
                                <th>Instructor</th>
                                {{-- <th class="cell-fit">Actions</th>--}}
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/ List DataTable -->
    </section>
    <!-- Dashboard Analytics end -->
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset('admin/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/charts/chart.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


    <script src="{{ asset('admin/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    {{--    <script src="{{ asset('admin/js/scripts/charts/chart-apex.js') }}"></script>--}}

    <script>
        $(document).ready(function () {


            var $productOrderChart = document.querySelector('#product-order-chart');
            var barChartEx = $('.bar-chart-ex'), tooltipShadow = 'rgba(0, 0, 0, 0.25)',
                labelColor = '#6e6b7b',
                successColorShade = '#28dac6',
                grid_line_color = 'rgba(200, 200, 200, 0.2)',
                flatPicker = $('.flat-picker'),
              $earningsChart = document.querySelector('#earnings-chart');
            var $textHeadingColor = '#5e5873';
            var earningsChartOptions;
            var $earningsStrokeColor2 = '#28c76f66';
            var $earningsStrokeColor3 = '#28c76f33';

            // Product Order Chart
            // -----------------------------
            orderChartOptions = {
                chart: {
                    height: 325,
                    type: 'radialBar'
                },
                colors: [window.colors.solid.primary, window.colors.solid.warning, window.colors.solid.danger],
                stroke: {
                    lineCap: 'round'
                },
                plotOptions: {
                    radialBar: {
                        size: 150,
                        hollow: {
                            size: '20%'
                        },
                        track: {
                            strokeWidth: '100%',
                            margin: 15
                        },
                        dataLabels: {
                            value: {
                                fontSize: '1rem',
                                colors: $textHeadingColor,
                                fontWeight: '500',
                                offsetY: 5
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '1.286rem',
                                colors: $textHeadingColor,
                                fontWeight: '500',

                                formatter: function (w) {
                                    // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                    return {{$coupons}};
                                }
                            }
                        }
                    }
                },
                series: [{{getProgress($couponsused,$coupons)}}, {{getProgress($couponsNotused,$coupons)}}],
                labels: ['Used', 'Not Used']
            };
            orderChart = new ApexCharts($productOrderChart, orderChartOptions);
            orderChart.render();

            var date = $('.bar-chart-ex').attr('data-date')
            var count = $('.bar-chart-ex').attr('data-count')
            let DateArr = [];
            JSON.parse(date).map((k, i) => {

                DateArr.push(k);
            })
            let DateCount = [];
            JSON.parse(count).map((k, i) => {

                DateCount.push(k);
            })

            // Bar Chart
            // --------------------------------------------------------------------
            if (barChartEx.length) {
                chartCoupon(DateArr, DateCount)
            }


            if (flatPicker.length) {
                var date = moment();
                console.log();

                flatPicker.each(function () {
                    $(this).flatpickr({
                        mode: 'range',
                        defaultDate: [date.format("YYYY-MM-DD"), date.subtract(1, 'M').format("YYYY-MM-DD")]
                    });
                });
            }
            flatpickr("#filterDate", {
                mode: "range",
                onChange: function (dateObj, dateStr) {
                    let startDate = moment(dateObj[0]).format("YYYY-MM-DD")
                    let endDate = moment(dateObj[1]).format("YYYY-MM-DD")
                    if (startDate && endDate) {
                        $.ajax({
                            url: `/admin/dashboard/fitterChart`,
                            type: 'get',
                            data: {
                                'start_date': startDate,
                                'end_date': endDate,
                            },
                            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                            success: (res) => {
                                let DateArr = [];
                                JSON.parse(res.date).map((k, i) => {

                                    DateArr.push(k);
                                })
                                let DateCount = [];
                                JSON.parse(res.count).map((k, i) => {

                                    DateCount.push(k);
                                })
                                chartCoupon(DateArr, DateCount)
                            }
                            ,
                            error: (data) => {
                                // console.log(data);
                            }
                        })
                    }

                }
            });

            function chartCoupon(DateArr, CountArr) {
                new Chart(barChartEx, {
                    type: 'bar',
                    options: {
                        elements: {
                            rectangle: {
                                borderWidth: 2,
                                borderSkipped: 'bottom'
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        responsiveAnimationDuration: 500,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            // Updated default tooltip UI
                            shadowOffsetX: 1,
                            shadowOffsetY: 1,
                            shadowBlur: 8,
                            shadowColor: tooltipShadow,
                            backgroundColor: window.colors.solid.white,
                            titleFontColor: window.colors.solid.black,
                            bodyFontColor: window.colors.solid.black
                        },
                        scales: {
                            xAxes: [
                                {
                                    display: true,
                                    gridLines: {
                                        display: true,
                                        color: grid_line_color,
                                        zeroLineColor: grid_line_color
                                    },
                                    scaleLabel: {
                                        display: false
                                    },
                                    ticks: {
                                        fontColor: labelColor
                                    }
                                }
                            ],
                            yAxes: [
                                {
                                    display: true,
                                    gridLines: {
                                        color: grid_line_color,
                                        zeroLineColor: grid_line_color
                                    },
                                    ticks: {
                                        stepSize: 30,
                                        min: 0,
                                        max: 150,
                                        fontColor: labelColor
                                    }
                                }
                            ]
                        }
                    },
                    data: {
                        labels: DateArr,
                        datasets: [
                            {
                                data: CountArr,
                                barThickness: 15,
                                backgroundColor: successColorShade,
                                borderColor: 'transparent'
                            }
                        ]
                    }
                });
            }
            var user_table
            user_table =  $('.coupon-list-table').DataTable({
               processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50],
                pageLength: 10,
                responsive: true,
                ordering:false,


                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-12 col-lg-8 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-4 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                // order: [[0, 'desc']],
                ajax: {
                    url: "{{route('get_students_coupons')}}",
                    data: function (d) {
                        d.grade_id_filter=$('#grade_id_filter').val();
                        d.couponUsed=$('#usedCoupon').val();
                    },
                },
                columns: [

                    {
                        data:'id',orderable: false, render:function (data,type,full){
                            return `<input class="uk-checkbox" id="checkbox" value="${data}" type="checkbox">`;}
                    },
                    {data: 'code', name: 'code', className: ' uk-text-left'  },
                    {data: 'price', name: 'price', className: ' uk-text-left'  },
                    {data: 'created_date', name: 'created_date', className: ' uk-text-left'  },
                    {data: 'used_date', name: 'used_date', className: ' uk-text-left'  },
                    {data: 'student', name: 'student', className: ' uk-text-left' },
                    {data: 'usage', name: 'created_at', className: ' uk-text-left' },
                    {data: 'instructor', name: 'instructor', className: ' uk-text-left' },
                ],
                // Buttons with Dropdown
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        // text: feather.icons['external-link'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                        text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                        exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] }

                    }

                ],

            });
            $('#grade_id_filter').on('change',function (){
                user_table.ajax.reload();
            });
            $('#usedCoupon').on('change',function (){
                user_table.ajax.reload();
            });

            earningsChartOptions = {
    chart: {
      type: 'donut',
      height: 120,
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    series: [{{$couponsusedThisMonthcprice}}],
    legend: { show: false },
    comparedResult: [2],
    labels: ['price'],
    stroke: { width: 0 },
    colors: [$earningsStrokeColor2],
    grid: {
      padding: {
        right: -20,
        bottom: -8,
        left: -20
      }
    },
    plotOptions: {
      pie: {
        startAngle: -10,
        donut: {
          labels: {
            show: true,
            name: {
              offsetY: 15
            },
            value: {
              offsetY: -15,
              formatter: function (val) {
                return parseInt(val) + '%';
              }
            },
            total: {
              show: true,
              offsetY: 15,
              label: 'Price',
              formatter: function (w) {
                return {{getProgress($couponsusedThisMonthcCount,$coupons)}} +'%';
              }
            }
          }
        }
      }
    },
    responsive: [
      {
        breakpoint: 1325,
        options: {
          chart: {
            height: 100
          }
        }
      },
      {
        breakpoint: 1200,
        options: {
          chart: {
            height: 120
          }
        }
      },
      {
        breakpoint: 1045,
        options: {
          chart: {
            height: 100
          }
        }
      },
      {
        breakpoint: 992,
        options: {
          chart: {
            height: 120
          }
        }
      }
    ]
  };
  earningsChart = new ApexCharts($earningsChart, earningsChartOptions);
  earningsChart.render();

        });

    </script>
@endsection
