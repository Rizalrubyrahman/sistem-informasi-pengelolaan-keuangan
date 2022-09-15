@extends('layouts.app')
@section('title','Dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin/css/dasboard.css') }}">
@endsection
@section('content')
@php
    foreach($products as $idP => $p){
        $dataProduct[] = $p;

        $jumlahProductTerjual[] = array_sum($jumlahPenjualanProduct[$idP]);
    }
@endphp
<h1 class="h3 mb-3"><strong>Dashboard</strong> </h1>
<div class="row">
    <div class="col-xl-12 col-xxl-7" >
        <div class="card d-flex w-100" id="banner-dashboard">
            <div class="card-body " >
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-7">
                        <div id="wdiget-container">
                            <div id="header-widget">
                                <span id="halo">Halo</span><span id="nickname"> {{ auth()->user()->name }}</span><br>
                                <span id="widget-subtitle">Selamat Datang Di Dashboard</span>
                            </div>
                            <div id="widget" class="row">
                                <div class="col-md-3" id="widget-item-1">
                                    <a href="{{ url('transaksi') }}" id="round-widget-1">
                                        <i class="fa-solid fa-file-circle-plus" id="icon-widget-1"></i>
                                    </a>
                                    <h6 id="widget-text-1">Catat <br> Transaksi</h6>
                                </div>
                                <div class="col-md-3" id="widget-item-2">
                                    <a href="{{ url('hutang') }}" id="round-widget-2">
                                        <i class="fa-solid fa-file-pen" id="icon-widget-2"></i>
                                    </a>
                                    <h6 id="widget-text-2">Catat <br> Hutang</h6>
                                </div>
                                <div class="col-md-3" id="widget-item-3">
                                    <a href="{{ url('produk') }}" id="round-widget-3">

                                        <i class="fa-solid fa-box" id="icon-widget-3"></i>
                                    </a>
                                    <h6 id="widget-text-3">Produk <br> &nbsp;</h6>
                                </div>
                                <div class="col-md-3" id="widget-item-4">
                                    <a href="{{ url('analisis_keuangan') }}" id="round-widget-4">
                                        <i class="fa-solid fa-chart-line" id="icon-widget-4"></i>
                                    </a>
                                    <h6 id="widget-text-4">Analisis <br> Keuangan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end">
                            <img id="imageGIF" src="{{ asset('admin/images/vector1.gif') }}" alt="" srcset="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="container-card">
            <div class="card mt-4 card-content">
                <div class="card-header" style="border-radius: 15px;">
                    <h5 class="card-title mb-0">Data Produk Terlaris</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="py-3">
                            <div class="chart chart-xs">
                                <canvas id="chartjs-dashboard-pie"></canvas>
                            </div>
                        </div>

                        <table class="table mb-0">
                            <tbody>
                                @foreach ($products as $idP => $p)
                                    <tr>
                                        <td>{{ $p }}</td>
                                        <td class="text-end">{{  array_sum($jumlahPenjualanProduct[$idP]) }}</td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="container-card">
            <div class="card mt-4 card-content">
                <div class="card-header" style="border-radius: 15px;">

                    <h5 class="card-title mb-0">Kalender</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100">
                        <div class="chart">
                            <div id="datetimepicker-dashboard"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="container-card">
            <div class="card mt-4 w-100 card-content">
                <div class="card-header" style="border-radius: 15px;">

                    <h5 class="card-title mb-0">Data Transaksi Tahun {{ \Carbon\Carbon::now()->format('Y') }}</h5>
                </div>
                <div class="card-body py-3">
                    <div class="chart chart-sm">
                        <canvas id="chartjs-dashboard-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php

@endphp
@endsection
@section('script')
<script>
    function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return 'Rp '+x1 + x2;
}
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(205,242,202,0)");
        gradient.addColorStop(1, "rgba(205,242,202,1)");
        // Line chart
        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Keuntungan",
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: "rgba(87,186,171,255)",
                    data: {!! json_encode($dataTransaction) !!}
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,

                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                fontSize: 10,
                callback: function (value, index, values) {
                    return addCommas(value); //! panggil function addComas tadi disini
                }
            },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pie chart
        new Chart(document.getElementById("chartjs-dashboard-pie"), {
            type: "pie",
            data: {
                labels: {!! json_encode($dataProduct) !!},
                datasets: [{
                    data: {!! json_encode($jumlahProductTerjual) !!},
                    backgroundColor: [
                        "#CDF0EA",
                        "#AF7AB3",
                        "#C4DFAA",
                        "#FFA1AC",
                        "#6DECB9",
                        "#4CACBC",
                        "#1D4D4F",
                        "#EE5007"
                    ],
                    borderWidth: 5
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 75
            }
        });
    });
</script>
@endsection
