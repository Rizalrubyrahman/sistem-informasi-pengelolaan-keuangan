@extends('layouts.app')
@section('title','Ubah Transaksi')
@section('content')
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
        margin: 1% auto;
        border: 1px solid #888;
        border-radius: 10px;
        width: 70%;
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
<div class="card shadow">
    <div class="card-body">
        <h1 class="h3 mb-3"><strong>Ubah Transaksi</strong> </h1>
        <div class="row">
            <div class="col-md-6 mt-4">
                <form action="{{ url('/transaksi/ubah/'.$saleTransaction->sale_transaction_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="sale_amount">Nominal Penjualan</label>
                        <input type="text" name="sale_amount" id="sale_amount" value="{{ old('sale_amount','Rp '.number_format($saleTransaction->sale_amount, 0, ",", ".") ) }}" class="form-control @error('sale_amount') is-invalid @enderror">
                        @error('sale_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="expense_amount">Nominal Pengeluaran</label>
                        <input type="text" name="expense_amount" id="expense_amount" value="{{ old('expense_amount','Rp '.number_format($saleTransaction->expense_amount, 0, ",", ".")) }}" class="form-control @error('expense_amount') is-invalid @enderror">
                        @error('expense_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="date">Tanggal Transaksi</label>
                                <input type="date"  name="date" id="date" value="{{ old('date',$saleTransaction->date) }}" class="form-control @error('date') is-invalid @enderror"/>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="note">Catatan/Keterangan</label>
                                <input type="text"  name="note" id="note" value="{{ old('note',$saleTransaction->note) }}" class="form-control @error('note') is-invalid @enderror"/>
                                @error('note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-control ">
                            @foreach ($paymentMethods as $paymentMethod)

                                <option value="{{ $paymentMethod->payment_method_id }}" {{ ($paymentMethod->payment_method_id == $saleTransaction->payment_method_id) ? 'selected' : '' }}> {{ $paymentMethod->payment_method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="sale_channel">Channel Penjualan</label>
                        <select name="sale_channel" id="sale_channel" class="form-control ">
                            @foreach ($saleChannels as $saleChannel)

                                <option value="{{ $saleChannel->sale_channel_id }}" {{ ($saleChannel->sale_channel_id == $saleTransaction->sale_channel_id) ? 'selected' : '' }}> {{ $saleChannel->sale_channel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <table  class="table" id="tableProduct">
                                <tr >
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center"><span style="margin-left: 10px">Qty</span></th>
                                    <th class="text-center"><span style="margin-left: 50px">Harga</span></th>
                                    <th></th>
                                </tr>
                                <tbody style="border:none">
                                    @php
                                        $no = 1;
                                    @endphp
                                    @forelse ($saleProducts as $saleProduct)
                                        <tr class="trProduct">
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <button class="btn btn-primary btn-sm" style="margin-top:3px;" id="btnSearch{{ $no }}" type="button"><i class="fas fa-search"></i></button>
                                                        <!-- Modal -->
                                                        <div class="modal" id="modal{{ $no }}" style="padding-top: 4%;">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h3><strong>Data Produk</strong></h3>
                                                                        <button id="iconCloseModal{{ $no }}" type="button" class="btn-close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div id="list">
                                                                            <table class="table">
                                                                                <tr>
                                                                                    <th class="text-center">No</th>
                                                                                    <th class="text-center">Nama Produk</th>
                                                                                    {{-- <th class="text-center">Stok</th> --}}
                                                                                    <th class="text-center">Harga</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                @php
                                                                                    $noProduct = 1;
                                                                                @endphp
                                                                                @forelse($products as $product)
                                                                                    <tr>
                                                                                        <td class="text-center">{{ $noProduct++ }}</td>
                                                                                        <td>{{ $product->product_name }}</td>
                                                                                        {{-- <td align="center">{{ $product->qty }}</td> --}}
                                                                                        <td align="center">Rp {{ number_format($product->product_price, 0, ",", ".") }}</td>
                                                                                        <td align="center">
                                                                                            {{-- @if ($product->qty == 0)
                                                                                                <div class="btn btn-sm" style="background-color: #e9ecef;border: 1px solid #ced4da;">Pilih</div>
                                                                                            @else --}}
                                                                                                <div id="btnPilih" produkId="{{ $product->product_id }}" produkNama="{{ $product->product_name }}" produkHarga="{{ $product->product_price }}" class="btn btn-primary btn-sm">Pilih</div>
                                                                                            {{-- @endif --}}
                                                                                            </td>
                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="5">Tidak Ada Produk</td>
                                                                                    </tr>
                                                                                @endforelse
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">

                                                                        <button id="buttonCloseModal{{ $no }}" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="hidden" value="{{ $saleProduct->product->product_id }}" name="produk_id[{{ $saleProduct->sale_transaction_product_id  }}]" id="produk_id{{ $no }}">
                                                        <input type="hidden" value="{{ $saleProduct->sale_transaction_product_id }}" name="sale_produk_id[{{ $saleProduct->sale_transaction_product_id }}]" id="sale_produk_id{{ $no }}">
                                                        <input  style="width: 100%; border:1px solid #ced4da" class="form-control" type="text" value="{{ $saleProduct->product->product_name }}" name="name_product[]" id="name_product{{ $no }}" readonly>
                                                    </div>
                                                    <div class="col-md-2"></div>
                                                </div>
                                                <div style="width: 220px"></div>

                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <button class="btn btn-primary btn-sm minus" style="margin-top:3px;" type="button" id="btnMin{{ $no }}"><i class="fas fa-minus"></i></button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input style="width: 50px;border:1px solid #ced4da; margin-left:-10px; text-align:center" value="{{ $saleProduct->qty }}" class="qty form-control" type="text" name="qty[{{ $saleProduct->sale_transaction_product_id }}]" id="qty{{ $no }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div style="width: 100px;"></div>
                                                        <button style="margin-top:3px;" class="btn btn-primary btn-sm" type="button" id="btnPlus{{ $no }}"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input style="margin-left: 50px; width:250px;" class="form-control" type="text" name="produk_price" value="Rp {{ number_format($saleProduct->product->product_price, 0, ",", ".") }}" readonly id="price{{ $no }}" >
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" type="button" id="btnClose{{ $no }}"><i class="fas fa-close"></i></button>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;

                                        @endphp
                                    @empty
                                    <tr class="trProduct">
                                        <td>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary btn-sm" style="margin-top:3px;" id="btnSearch1" type="button"><i class="fas fa-search"></i></button>
                                                    <!-- Modal -->
                                                    <div class="modal" id="modal1" style="padding-top: 4%;">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h3><strong>Data Produk</strong></h3>
                                                                    <button id="iconCloseModal1" type="button" class="btn-close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div id="list">
                                                                        <table class="table">
                                                                            <tr>
                                                                                <th class="text-center">No</th>
                                                                                <th class="text-center">Nama Produk</th>
                                                                                {{-- <th class="text-center">Stok</th> --}}
                                                                                <th class="text-center">Harga</th>
                                                                                <th></th>
                                                                            </tr>
                                                                            @php
                                                                                $noProduct = 1;
                                                                            @endphp
                                                                            @forelse($products as $product)
                                                                                <tr>
                                                                                    <td class="text-center">{{ $noProduct++ }}</td>
                                                                                    <td>{{ $product->product_name }}</td>
                                                                                    {{-- <td align="center">{{ $product->qty }}</td> --}}
                                                                                    <td align="center">Rp {{ number_format($product->product_price, 0, ",", ".") }}</td>
                                                                                    <td align="center">
                                                                                        {{-- @if ($product->qty == 0)
                                                                                            <div class="btn btn-sm" style="background-color: #e9ecef;border: 1px solid #ced4da;">Pilih</div>
                                                                                        @else --}}
                                                                                            <div id="btnPilih" produkId="{{ $product->product_id }}" produkNama="{{ $product->product_name }}" produkHarga="{{ $product->product_price }}" class="btn btn-primary btn-sm">Pilih</div>
                                                                                        {{-- @endif --}}
                                                                                    </td>
                                                                                </tr>
                                                                            @empty
                                                                                <tr>
                                                                                    <td colspan="5">Tidak Ada Produk</td>
                                                                                </tr>
                                                                            @endforelse
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">

                                                                    <button id="buttonCloseModal1" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="hidden" value="" name="produk_id[]" id="produk_id1">
                                                    <input type="hidden" value="" name="sale_produk_id[]" id="sale_produk_id1">
                                                    <input  style="width: 100%; border:1px solid #ced4da" class="form-control" type="text" value="" name="name_product[]" id="name_product1" readonly>
                                                </div>
                                                <div class="col-md-2"></div>
                                            </div>
                                            <div style="width: 220px"></div>

                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary btn-sm minus" style="margin-top:3px;" type="button" id="btnMin1"><i class="fas fa-minus"></i></button>
                                                </div>
                                                <div class="col-md-4">
                                                    <input style="width: 50px;border:1px solid #ced4da; margin-left:-10px; text-align:center" value="0" class="qty form-control" type="text" name="qty[]" id="qty1">
                                                </div>
                                                <div class="col-md-4">
                                                    <div style="width: 100px;"></div>
                                                    <button style="margin-top:3px;" class="btn btn-primary btn-sm" type="button" id="btnPlus1"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input style="margin-left: 50px; width:250px;" class="form-control" type="text" name="produk_price" value="" readonly id="price1" >
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" type="button" id="btnClose1"><i class="fas fa-close"></i></button>
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <button id="btnAddRow" style="margin-left:165px" class="btn btn-success" type="button">Tambah Produk</button>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label>Bukti Pembayaran</label>
                                <input type="file" " name="bukti_pembayaran" id="bukti_pembayaran" style="width:300px" class="form-control @error('bukti_pembayaran') is-invalid @enderror">
                                <span style="color:#dc3545; font-size:8pt"><required>*</required>Format yang diijinkan (JPG, PNG, JPEG)</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <img src="{{ asset('admin/images/bukti_pembayaran/'.$saleTransaction->file) }}" alt="" style="width:400px">
                            </div>
                            <div class="col-md-"></div>
                        </div>

                    </div>

                    <br>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-warning">Ubah</button>
                        <a href="{{ url('transaksi/detail/'.$saleTransaction->sale_transaction_id) }}" style="border-color: #1cbb8c" class="btn">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

    <script>

        $("#btnAddRow").click(function() {
            var lastRow = $('#tableProduct>tbody>tr:last');
            var cloned = lastRow.clone();
            cloned.find('input, select, button,.modal').each(function () {
                var id = $(this).attr('id');
                var regIdMatch = /^(.+)(\d+)$/;
                var aIdParts = id.match(regIdMatch);
                index = (parseInt(aIdParts[2],10) + 1);
                var newId = aIdParts[1] + index;

                $(this).attr('id', newId);

            });

            cloned.find("input[type='text']").val('');
            cloned.find("input[type='hidden']").val('');
            cloned.find(".qty:input[type='text']").val('0');
            cloned.insertAfter(lastRow);
            $('#qty'+index).removeAttr('name');
            $('#qty'+index).attr("name","qty[]");
            $('#produk_id'+index).removeAttr('name');
            $('#produk_id'+index).attr("name","produk_id[]");
            $('#sale_produk_id'+index).removeAttr('name');
            $('#sale_produk_id'+index).attr("name","sale_produk_id[]");
            minus(index);
            plus(index);
            close(index);
            modal(index);
            ValidateQty(index);
        });
        function minus(index){
            $("#btnMin"+index).click(function() {
                let valQty = $("#qty"+index).val();
                if (valQty > 0) {
                    var newVal = valQty - 1;
                } else {
                    newVal = 0;
                }
                $("#qty"+index).val(newVal);
            });

        }

        function close(index){
            $("#tableProduct").on("click", "#btnClose"+index, function(event) {
                var row = $('#tableProduct>tbody>tr').length;
                if(row > 2){
                    $(this).closest("tr").remove();
                    index -= 1
                }
            });
        }
        function plus(index){
            $("#btnPlus"+index).click(function() {
                let valQty = $("#qty"+index).val();
                var newVal = parseInt(valQty) + 1;
                $("#qty"+index).val(newVal);
            });
        }
        function ValidateQty(index){
            $("#qty"+index).keyup(function(e)
                                {
            if (/\D/g.test(this.value))
            {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
            });
        }
        function modal(index){
            var btnModal = document.getElementById('btnSearch'+index);
            var modal = document.getElementById('modal'+index);
            btnModal.onclick = function() {
                modal.style.display = 'block';
                var iconCloseModal = document.getElementById('iconCloseModal'+index);
                iconCloseModal.onclick = function() {
                    modal.style.display = 'none';
                }
                var buttonCloseModal = document.getElementById('buttonCloseModal'+index);
                buttonCloseModal.onclick = function() {
                    modal.style.display = 'none';
                }
                window.onclick = function(e) {
                    if (e.target == modal) {
                        modal.style.display = "none";
                    }
                }
                let indexProduk = index;
                let indexHarga = index;
                let indexProdukId = index;
                $(document).on('click','#btnPilih',function(e){
                    let valProdukName= $("#name_product"+indexProduk);
                    let newValProdukName = $(this).attr('produkNama');
                    $("#name_product"+indexProduk).val(newValProdukName);

                    let valProdukHarga= $("#price"+indexHarga);
                    let newValProdukHarga = $(this).attr('produkHarga');
                    $("#price"+indexHarga).val(formatRupiah(newValProdukHarga), 'Rp ');

                    let valProdukId= $("#produk_id"+indexProdukId);
                    let newValProdukId = $(this).attr('produkId');
                    $("#produk_id"+indexProdukId).val(newValProdukId);

                    indexProduk = 0;
                    indexHarga = 0;
                    indexProdukId = 0;
                    modal.style.display = "none";
                });
            }
        }
        function formatRupiah(number, prefix)
        {
            var number_string = number.replace(/[^,\d]/g, '').toString(),
                split    = number_string.split(','),
                sisa     = split[0].length % 3,
                rupiah     = split[0].substr(0, sisa),
                ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? 'Rp ' + rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
        let countProducts = {{ count($saleProducts) }}
        if(countProducts == 0){
            ValidateQty(1);
            minus(1);
            plus(1);
            close(1);
            modal(1);
        }else{
            for (let x = 1; x <= countProducts; x++) {
            ValidateQty(x);
            minus(x);
            plus(x);
            close(x);
            modal(x);
        }
        }

        var saleAmount = document.getElementById('sale_amount');
        saleAmount.addEventListener('keyup', function(e)
        {
            saleAmount.value = formatRupiah(this.value, 'Rp ');
        });
        var expenseAmount = document.getElementById('expense_amount');
        expenseAmount.addEventListener('keyup', function(e)
        {
            expenseAmount.value = formatRupiah(this.value, 'Rp ');
        });
    </script>
@endsection

