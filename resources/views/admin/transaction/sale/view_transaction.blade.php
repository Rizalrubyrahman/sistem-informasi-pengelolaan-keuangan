@extends('layouts.app')
@section('title','Transaksi')
@php
    $sumSale = 0;
    $sumExpense = 0;
    foreach($saleTransactions as $saleTransaction){
        $dateSaleTransaction[$saleTransaction->date] = $saleTransaction->date;
        $getSaleTransactions[$saleTransaction->date][$saleTransaction->sale_transaction_id] =
            [
                'Penjualan' => $saleTransaction->sale_amount,
                'Pengeluaran' => $saleTransaction->expense_amount,
                'id' => $saleTransaction->sale_transaction_id,
                'note' => $saleTransaction->note,
            ];
        $sumSale += $saleTransaction->sale_amount;
        $sumExpense += $saleTransaction->expense_amount;
    }
    foreach ($getSaleTransactions as $date => $getTransaction) {
        foreach ($getTransaction as $transaction) {
            $sumSaleToday[$date][] = $transaction['Penjualan'];
            $sumExpenseToday[$date][] = $transaction['Pengeluaran'];
        }

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
<h1 class="h3 mb-3"><strong>Transaksi</strong> </h1>
<div class="card shadow" style="border-radius: 15px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table" style="border: 2px solid #dee2e6; width:100%;">
                    <tr>
                        <td align="center" style="border: 2px solid #dee2e6;">
                            @if ($sumSale == 0 || $sumSale == null)
                                -
                            @else
                                <span style="color:#2ab284">Rp {{ number_format($sumSale, 0, ",", ".") }}</span>
                            @endif
                            <br>
                            <span style="font-size: 9pt">Penjualan</span>
                        </td>
                        <td align="center" style="border: 2px solid #dee2e6;">
                            @if ($sumExpense == 0 || $sumExpense == null)
                                -
                            @else
                                <span style="color:#dc4e4d">Rp {{ number_format($sumExpense, 0, ",", ".") }}</span>
                            @endif
                            <br>
                            <span style="font-size: 9pt">Pengeluaran</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if (($sumSale - $sumExpense) > 0)
                                <span style="color:#2ab284">Keuntungan</span>
                            @elseif (($sumSale - $sumExpense) < 0)
                                <span style="color:#dc4e4d">Keuntungan</span>
                            @else
                                Keuntungan
                            @endif
                        </td>
                        <td align="right">
                            @if (($sumSale - $sumExpense) > 0)
                                <span style="color:#2ab284">Rp {{ number_format(($sumSale - $sumExpense), 0, ",", ".") }}</span>
                            @elseif (($sumSale - $sumExpense) < 0)
                                <span style="color:#dc4e4d">Rp {{ number_format(($sumSale - $sumExpense), 0, ",", ".") }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr style="height: 10px;padding:0">
                        <td colspan="2" style="height: 10px;padding:0">
                            <a href="">
                                <button class="btn " style="height: 30px; width:100%;">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            Lihat Grafik Analisa
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-angle-right"></i>
                                        </span>
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
                <a href="{{ url('/stok_barang/tambah') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i> Tambah Transaksi</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" name="fromDate" id="fromDate" class="datepicker form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="toDate" id="toDate" class="datepicker form-control">
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 mt-3">
                <div class="d-flex justify-content-end">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group" >
                            <input type="text" class="form-control" onkeyup="showIconClear(this)" style="border-right:0px;border-color: #1cbb8c" placeholder="Cari transaksi" aria-label="Cari barang" id="transactionSearch" aria-describedby="button-addon2">
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
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <select name="filter" id="filter"  class="form-select" style="border-color: #1cbb8c;width:130px;">
                            <option value="">Filter</option>
                            <option value="1">Semua</option>
                            <option value="2">Lunas</option>
                            <option value="3">Belum Lunas</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <table class="table mt-4">
            <tr style="background-color: #f3f3f3">
                <th style="text-align:center">Tanggal</th>
                <th style="text-align:center">Penjualan</th>
                <th style="text-align:center">Pengeluaran</th>
                <th style="text-align:center">Opsi</th>
            </tr>
            <tbody id="list">
                @if ( $saleTransactions->count() > 0)
                    @foreach ($dateSaleTransaction as $date)
                        <tr style="background-color: #f3fbfd">
                            <th style="text-align:center">

                                {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</th>
                            <th style="text-align:center">
                                @if (array_sum($sumSaleToday[$date]) == 0 || array_sum($sumSaleToday[$date]) == null)
                                    -
                                @else
                                    <span style="color:#2ab284">Rp {{ number_format(array_sum($sumSaleToday[$date]), 0, ",", ".") }}</span>
                                @endif
                            </th>
                            <th style="text-align:center">
                                @if (array_sum($sumExpenseToday[$date]) == 0 || array_sum($sumExpenseToday[$date]) == null)
                                    -
                                @else
                                    <span style="color:#dc4e4d">Rp {{ number_format(array_sum($sumExpenseToday[$date]), 0, ",", ".") }}</span>
                                @endif
                            </th>
                            <th></th>
                            {{-- <td align="center">
                                <a href="{{ url('/stok_barang/ubah/'.$product->product_id) }}" title="Edit" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a>

                                <button type="button" class="btn btn-danger btnProductModalDelete" productId="{{ $product->product_id }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <!-- Modal -->
                                <div class="modal" id="productModalDelete{{ $product->product_id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3><strong>Hapus Produk</strong></h3>
                                                <button id="iconCloseModalDelete{{ $product->product_id }}" type="button" class="btn-close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Anda yakin ingin menghapus <strong>{{ $product->product_name }}</strong> ?
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ url('/stok_barang/hapus/'.$product->product_id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                                <button id="buttonCloseModalDelete{{ $product->product_id }}" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td> --}}
                        </tr>
                        @foreach ($getSaleTransactions[$date] as $getSaleTransaction)
                            <tr>
                                <td align="center">
                                    @if ($getSaleTransaction['note'] == null)
                                        Penjualan
                                    @else
                                        {{ $getSaleTransaction['note']}}
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($getSaleTransaction['Penjualan'] == 0 || $getSaleTransaction['Penjualan'] == null)
                                        -
                                    @else
                                        <span style="color:#2ab284">Rp {{ number_format($getSaleTransaction['Penjualan'], 0, ",", ".") }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($getSaleTransaction['Pengeluaran'] == 0 || $getSaleTransaction['Pengeluaran'] == null)
                                        -
                                    @else
                                        <span style="color:#dc4e4d">Rp {{ number_format($getSaleTransaction['Pengeluaran'], 0, ",", ".") }}</span>
                                    @endif
                                </td>
                                <td align="center"><a href="{{ url('/transaksi/detail/'.$getSaleTransaction['id']) }}" title="Detail" class="btn btn-primary"><i class="fas fa-file"></i></a></td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                     <th colspan="4">Tidak ada transaksi</th>
                @endif
            </tbody>
        </table>
        <div class="form-group mt-4">
            <a href="" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Download PDF</a>
            <a href="" class="btn btn-success"><i class="fas fa-file-excel"></i> Download Excel</a>

        </div>
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

        $('#transactionSearch').on('keyup',function(){
            $value=$(this).val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/transaksi/cari")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('#list').html(data);
                    }
            });

        })
        $('#filter').on('change',function(){
            $value=$(this).val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/transaksi/filter")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('#list').html(data);
                    }
            });

        })
        $('#sortBy').on('change',function(){
            $value=$(this).val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/transaksi/sort_by")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('#list').html(data);
                    }
            });

        })
        $('#fromDate,#toDate').on('change',function(){
            $toDate=$('#toDate').val();
            $fromDate=$('#fromDate').val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/transaksi/sort_by_date")}}',
                    data:{'fromDate':$fromDate,'toDate':$toDate},
                    success:function(data){
                        $('#list').html(data);
                    }
            });

        })
        $('#btnClear').on('click',function(){
                $value = $('#transactionSearch').val('');
                $("#clear").css("display", "none");
                $.ajax({
                    type : 'get',
                    url : '{{url("/transaksi/cari")}}',
                    data:{'search':null},
                    success:function(data){
                        $('#list').html(data);
                    }
            });
        });

    </script>

@endsection
