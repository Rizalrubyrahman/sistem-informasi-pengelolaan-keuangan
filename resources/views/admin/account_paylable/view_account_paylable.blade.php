@extends('layouts.app')
@section('title','Piutang')
@php
    $sumPay = 0;
    $sumDebt = 0;
    foreach($accountPaylables as $accountPaylable){
        $sumDebt += $accountPaylable->debt;
        $sumPay += $accountPaylable->pay;
    }


@endphp
@section('style')
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-dialog {
            background-color: #fefefe;
            margin: 5% auto;
            border: 1px solid #888;
            border-radius: 10px;
            width: 40%;
        }
        .modal-header{
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: var(--bs-modal-inner-border-radius);
            border-top-right-radius: var(--bs-modal-inner-border-radius);

        }
        .modal-body{
            position: relative;
            flex: 1 1 auto;
            padding: 20px;
        }
        .modal-footer{
            display: flex;
            flex-shrink: 0;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            padding: 20px;
            background-color: var(--bs-modal-footer-bg);
            border-top: 1px solid #dee2e6;
            border-bottom-right-radius: var(--bs-modal-inner-border-radius);
            border-bottom-left-radius: var(--bs-modal-inner-border-radius);
        }
    </style>
@endsection
@section('content')
<h1 class="h3 mb-3"><strong>Piutang</strong> </h1>
<div class="card shadow" style="border-radius: 15px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table" style="border: 2px solid #dee2e6; width:100%;">
                    <tr>
                        <td align="center" style="border: 2px solid #dee2e6;">
                            @if (array_sum($allTotalBayar) == 0 || array_sum($allTotalBayar) == null)
                                <span id="spanPenjualan">-</span>
                            @else
                                <span id="spanPenjualan" style="color:#2ab284">Rp {{ number_format(array_sum($allTotalBayar), 0, ",", ".") }}</span>
                            @endif
                            <br>
                            <span style="font-size: 9pt">Sudah Bayar</span>
                        </td>
                        <td align="center" style="border: 2px solid #dee2e6;">
                            @if ($sumDebt == 0 || $sumDebt == null)
                                <span id="spanPengeluaran">-</span>
                            @else
                                 <span style="color:#dc4e4d" id="spanPengeluaran"> Rp {{ number_format($sumDebt, 0, ",", ".") }}</span>
                            @endif
                            <br>
                            <span style="font-size: 9pt">Hutang Pelanggan</span>
                        </td>
                    </tr>

                    <tr style="height: 10px;padding:0">
                        <td colspan="2" style="height: 10px;padding:0">
                            <a href="">
                                <button class="btn " style="height: 30px; width:100%;">
                                    <div class="d-flex justify-content-between">
                                        Sisa Hutang

                                        @if ($sumDebt == array_sum($allTotalBayar))
                                            <span id="spanPengeluaran">-</span>
                                        @else
                                            <span style="color:#dc4e4d" id="spanPengeluaran"> Rp {{ number_format($sumDebt - array_sum($allTotalBayar), 0, ",", ".") }}</span>
                                        @endif
                                    </div>

                                </button>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ url('/hutang/tambah') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i> Tambah Piutang</a>
            </div>
        </div>
        <form action="{{ url('/hutang/export_pdf') }}" method="get">
            @csrf
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="debtDate" id="debtDate" value="" class="datepicker form-control">
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-md-6 mt-3">
                <div class="d-flex justify-content-end">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group" >
                            <input type="text" class="form-control" onkeyup="showIconClear(this)" style="border-right:0px;border-color: #1cbb8c" placeholder="Cari Nama Pelanggan" aria-label="Cari barang" id="cari_piutang" aria-describedby="button-addon2">
                            <button class="btn" style="border-left:0px;border-color: #1cbb8c" type="button" id="btnClear"><span id="clear" style="display: none;"><i class="fa-solid fa-xmark"></i></span></button>
                        </div>
                    </div>
                    <div>&nbsp;</div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <select name="sortBy" id="sortBy"  class="form-select" style="border-color: #1cbb8c;width:130px;">
                            <option value="">Urutkan</option>
                            <option value="1">Terbaru</option>
                            <option value="2">Terlama</option>
                        </select>
                    </div>
                    <div>&nbsp;</div>


                </div>
            </div>
        </div>
        <table class="table mt-4">
            <tr style="background-color: #f3f3f3">
                <th style="text-align:center">Tanggal</th>
                <th style="text-align:center">Tanggal <br> Jatuh Tempo</th>

                <th style="text-align:center">Nama Pelanggan</th>
                <th style="text-align:center">Hutang</th>
                <th style="text-align:center">Bayar</th>
                <th style="text-align:center">Sisa</th>
                <th style="text-align:center">status</th>
                <th style="text-align:center">Opsi</th>
            </tr>
            <tbody id="list">
                @if ( $accountPaylables->count() > 0)
                    @foreach ($accountPaylables as $ap)
                        <tr>
                            <td align="center">
                                    <span>{{ \Carbon\Carbon::parse($ap->debt_date)->format('d-m-Y') }}</span>
                            </td>
                            <td align="center">
                                <span>{{ ($ap->due_date == null) ? '-' : \Carbon\Carbon::parse($ap->due_date)->format('d-m-Y') }}</span>
                            </td>

                            <td>
                                <span>{{ $ap->customer_name }}</span>
                            </td>

                            <td align="center">
                                @if ($ap->debt == 0 || $ap->debt == null)
                                    -
                                @else
                                    <span style="color:#dc4e4d">Rp {{ number_format($ap->debt, 0, ",", ".") }}</span>
                                @endif
                            </td>
                            <td align="center">
                                @if (array_sum($totalBayar[$ap->account_paylable_id]) == 0)
                                    -
                                @else
                                    <span style="color:#2ab284">Rp {{ number_format(array_sum($totalBayar[$ap->account_paylable_id]), 0, ",", ".") }}</span>
                                @endif
                            </td>
                            <td align="center">
                                @php
                                    $payCustomer = (array_sum($totalBayar[$ap->account_paylable_id]) == 0) ? 0 : array_sum($totalBayar[$ap->account_paylable_id]);
                                @endphp

                                @if($ap->debt > $payCustomer)
                                    <span style="color:#dc4e4d">Rp {{ number_format(($ap->debt - $payCustomer), 0, ",", ".") }}</span>
                                @elseif($ap->debt == array_sum($totalBayar[$ap->account_paylable_id]))
                                    Rp 0
                                @endif
                            </td>
                            <td align="center">
                                @if ($ap->status == 'Lunas')
                                <div style="background-color: #2ab284;color:white;border-radius:5px;padding:1px;">{{ $ap->status }}</div>
                                @else
                                <div style="background-color: #dc4e4d;color:white;border-radius:5px;padding:1px;">{{ $ap->status }}</div>
                                @endif
                            </td>
                            <td align="center">
                                <a href="{{ url('/hutang/detail/'.$ap->account_paylable_id) }}" class="btn btn-sm btn-primary" style="border-radius: 3px"><i class="fas fa-file-alt align-middle"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                     <th colspan="4">Tidak ada Piutang</th>
                @endif
            </tbody>
        </table>
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-danger" id="btnExportPDF" formtarget="_blank"><i class="fas fa-file-pdf" ></i> Unduh PDF</button>
    </form>

            {{-- <button type="button" class="btn btn-success" id="btnExportExcel"><i class="fas fa-file-excel"></i> Unduh Excel</button> --}}

        </div>
        <input type="hidden" name="date" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" id="dateNow">

        {{-- <nav class="d-flex justify-content-center" aria-label="Page navigation example">
        {{ $products->links() }}
        </nav> --}}
    </div>
</div>

@endsection
@section('script')
    <script>
        var btnDelete = document.querySelectorAll('.btnProductModalDelete');
        btnDelete.forEach(e => {
            var modalDelete = document.getElementById('productModalDelete'+e.getAttribute('productId'));
            e.onclick = function() {
                modalDelete.style.display = 'block';

                var iconCloseModalDelete = document.getElementById('iconCloseModalDelete'+e.getAttribute('productId'));
                iconCloseModalDelete.onclick = function() {
                    modalDelete.style.display = 'none';
                }

                var buttonCloseModalDelete = document.getElementById('buttonCloseModalDelete'+e.getAttribute('productId'));
                buttonCloseModalDelete.onclick = function() {
                    modalDelete.style.display = 'none';
                }
                window.onclick = function(e) {
                    if (e.target == modalDelete) {
                        modalDelete.style.display = "none";
                    }
                }
            }


        });


    </script>
   <script>
        function showIconClear(inputSearch){
            const iconClear = document.getElementById('clear');
            if(inputSearch.value.length == 0){
                iconClear.style.display = "none";
            }else{
                iconClear.style.display = "";
            }

        }

   </script>
    <script type="text/javascript">
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
    <script type="text/javascript">



        $('#sortBy').on('change',function(){
            $value=$(this).val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/hutang/sort_by")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('#list').html(data);
                    }
            });

        })
        function loadDataDate(){
            $debtDate=$('#debtDate').val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/hutang/sort_by_date")}}',
                    data:{'debtDate':$debtDate},
                    success:function(data){
                        $('#list').html(data);
                    }
            });
        }

        $('#debtDate').on('change',function(){
            loadDataDate();
        })

        $('#btnExportExcel').on('click',function(){
            $toDate=$('#toDate').val();
            $fromDate=$('#fromDate').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    method : 'post',
                    url : '{{url("/hutang/export_excel")}}',
                    data:{_token: CSRF_TOKEN,'fromDate':$fromDate,'toDate':$toDate},
                    xhrFields:{
                        responseType: 'blob'
                    },
                                success: function(data)
                    {
                        var link = document.createElement('a');
                        var date = $('#dateNow').val();
                        link.href = window.URL.createObjectURL(data);
                        link.download = `Laporan Laporan Laba Rugi.`+date+`.xlsx`;
                        link.click();
                    },
                    fail: function(data) {
                        alert('Not downloaded');
                        //console.log('fail',  data);
                    }

            });
        })


        $('#cari_piutang').on('keyup',function(){
            $value=$(this).val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/hutang/cari")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('#list').html(data);
                    }
            });

        });
        $('#btnClear').on('click',function(){
                $value = $('#cari_piutang').val('');
                $("#clear").css("display", "none");
                $.ajax({
                    type : 'get',
                    url : '{{url("/hutang/cari")}}',
                    data:{'search':null},
                    success:function(data){
                        $('#list').html(data);
                    }
            });
        });
    </script>

@endsection
