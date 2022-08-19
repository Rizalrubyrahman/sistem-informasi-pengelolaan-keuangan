@extends('layouts.app')
@section('title','Dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin/css/dasboard.css') }}">
@endsection
@section('content')
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
                                    <div id="round-widget-1">
                                        <i class="fa-solid fa-file-circle-plus" id="icon-widget-1"></i>
                                    </div>
                                    <h6 id="widget-text-1">Catat <br> Transaksi</h6>
                                </div>
                                <div class="col-md-3" id="widget-item-2">
                                    <div id="round-widget-2">
                                        <i class="fa-solid fa-file-pen" id="icon-widget-2"></i>
                                    </div>
                                    <h6 id="widget-text-2">Catat <br> Hutang</h6>
                                </div>
                                <div class="col-md-3" id="widget-item-3">
                                    <div id="round-widget-3">

                                        <i class="fa-solid fa-box" id="icon-widget-3"></i>
                                    </div>
                                    <h6 id="widget-text-3">Stok <br> Barang</h6>
                                </div>
                                <div class="col-md-3" id="widget-item-4">
                                    <div id="round-widget-4">
                                        <i class="fa-solid fa-chart-line" id="icon-widget-4"></i>
                                    </div>
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
                    <h5 class="card-title mb-0">Data Produk</h5>
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
                                <tr>
                                    <td>Chrome</td>
                                    <td class="text-end">4306</td>
                                </tr>
                                <tr>
                                    <td>Firefox</td>
                                    <td class="text-end">3801</td>
                                </tr>
                                <tr>
                                    <td>IE</td>
                                    <td class="text-end">1689</td>
                                </tr>
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

                    <h5 class="card-title mb-0">Recent Movement</h5>
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
@endsection
