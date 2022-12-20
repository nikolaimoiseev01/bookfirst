@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="justify-content-between d-flex">
                <h1 class="m-0">Статистика "Первая Книга"</h1>
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }
                </style>

            </div>

        </div><!-- /.container-fluid -->
    </div>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">


            <div class="mb-3 card">
                <div class="bg-gradient-info card-header">
                    <h1 style="font-size: 25px;" class="card-title">Количество лайков и комментариев</h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="display: block;">
                    <div class="row">
                        <div class="col-lg-12">

                            <div id="chart_likes_and_comments">
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-footer -->
            </div>


            <div class="mb-3 card">
                <div class="bg-gradient-info card-header">
                    <h1 style="font-size: 25px;" class="card-title">Загружено работ</h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="display: block;">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="chart_works_uploaded">
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-footer -->
            </div>



        </div>


        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            var options = {
                series: [{
                    name: 'Вручную',
                    data: {!! json_encode($data_works_uploaded->pluck('works_cnt_manual')) !!}
                }, {
                    name: 'Из документа',
                    data: {!! json_encode($data_works_uploaded->pluck('works_cnt_doc')) !!}
                }, {
                    name: 'Не определено',
                    data: {!! json_encode($data_works_uploaded->pluck('works_cnt_other')) !!}
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    width: '100%',
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        dataLabels: {
                            total: {
                                enabled: true,
                                offsetX: 0,
                                style: {
                                    fontSize: '13px',
                                    fontWeight: 900
                                }
                            }
                        }
                    },
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },

                xaxis: {
                    categories: {!! json_encode($data_works_uploaded->pluck('date')) !!},
                    labels: {
                        formatter: function (val) {
                            return val
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: undefined
                    },
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart_works_uploaded"), options);
            chart.render();
        </script>


        <script>
            var options = {
                series: [{
                    name: "Лайков",
                    data: {!! json_encode($data_likes_and_comments->pluck('works_likes_cnt')) !!}
                }, {
                    name: "Комментариев",
                    data: {!! json_encode($data_likes_and_comments->pluck('works_comments_cnt')) !!}
                }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: {!! json_encode($data_likes_and_comments->pluck('date')) !!},
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart_likes_and_comments"), options);
            chart.render();

        </script>


    </section>
    <!-- /.content -->
@endsection
