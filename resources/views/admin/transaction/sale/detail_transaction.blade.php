@extends('layouts.app')
@section('title','Detail Transaksi')
@section('content')
<style>
    #btnImage {
        width: 120px;
        height: 120px;
        float: left;
        margin: 0;
        padding: 0;
        cursor: pointer;
        text-align: center;
        position: relative;
        border-color:  white;
    }

    #btnImage img {
        width: 120px;
        height: 120px;
        border-color:  white;

    }
    #btnImage span {
        position: absolute;
        width: 120px;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;

    }
    #btnImage:hover img {
        opacity: 0.4;
    }
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
<div class="card shadow">
    <div class="card-body">
        <h1 class="h3 mb-3"><strong>Detail Transaksi</strong> </h1>
        <div class="row">
            <div class="col-md-6 mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <span style="background-color: #d5f5e8;padding:5px 12px 5px 12px;border-radius:3px;"><i style="color:#08bf7f" class="fa-solid fa-caret-right"></i></span> Penjualan
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        @if ($saleTransaction->sale_amount == null)
                            Rp 0
                        @else
                            Rp {{ number_format($saleTransaction->sale_amount, 0, ",", ".") }}
                        @endif
                    </div>
                    <div class="col-md-6 mt-4">
                        <span style="background-color: #fcded6;padding:5px 12px 5px 12px;"><i style="color:#ef3b11" class="fa-solid fa-caret-left"></i></span> Pengeluaran
                    </div>
                    <div class="col-md-6 mt-4 d-flex justify-content-end">
                        @if ($saleTransaction->expense_amount == null)
                            Rp 0
                        @else
                            Rp {{ number_format($saleTransaction->expense_amount, 0, ",", ".") }}
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Keuntungan</strong>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        @if (($saleTransaction->sale_amount - $saleTransaction->expense_amount) < 0)
                            <span style="color:#dc4e4d">Rp {{ number_format(($saleTransaction->sale_amount - $saleTransaction->expense_amount), 0, ",", ".") }}</span>
                        @elseif(($saleTransaction->sale_amount - $saleTransaction->expense_amount) > 0)
                            <span style="color:#2ab284">Rp {{ number_format(($saleTransaction->sale_amount - $saleTransaction->expense_amount), 0, ",", ".") }}</span>
                        @else
                            Rp 0
                        @endif
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        Catatan
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        Metode Pembayaran
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @if ($saleTransaction->note == null)
                            <strong>-</strong>
                        @else
                            <strong>{{ $saleTransaction->note }}</strong>
                        @endif
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                       <i class="fas {{ $saleTransaction->paymentMethod->icon }}" style="font-size: 18px; margin-right:5px"></i> <span class="ml-4"><strong>{{ $saleTransaction->paymentMethod->payment_method }}</strong></span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                       Channel Penjualan
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        Bukti Pembayaran
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                       <img src="{{ asset('admin/images/icon/'.$saleTransaction->saleChannel->icon) }}" style="height:20px;width:20px;" alt="Icon Channel Penjualan">  <strong>{{ $saleTransaction->saleChannel->sale_channel }}</strong>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <button class="btn" data-toggle="modal" data-target="#exampleModal" id="btnImage"><img src="{{ asset('admin/images/bukti_pembayaran/'.$saleTransaction->file) }}" style="width: 100px; height:100px;" alt="Bukti Pembayaran"><span><i style="font-size: 20px; color:black" class="fa-solid fa-magnifying-glass"></i></span></button>
                        <!-- Modal -->
                        <div class="modal" id="exampleModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3><strong>Bukti Pembayaran</strong></h3>
                                        <button id="iconCloseModal" type="button" class="btn-close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset('admin/images/bukti_pembayaran/'.$saleTransaction->file) }}" style="height: 400px;width: 400px">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="buttonCloseModal" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <a href="{{ url('/transaksi/ubah/'.$saleTransaction->sale_transaction_id) }}" title="Edit" class="btn btn-warning"><i class="fa-solid fa-pencil"></i> Ubah Transaksi</a>
                    <button type="button" class="btn btn-danger" id="btnModalDelete">
                        <i class="fa-solid fa-trash-can"></i> Hapus Transaksi
                    </button>
                    <a  href="{{ url('transaksi') }}" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</a>
                    <!-- Modal -->
                    <div class="modal" id="modalDelete" style="padding-top: 4%">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3><strong>Hapus Transaksi</strong></h3>
                                    <button id="iconCloseModalDelete" type="button" class="btn-close"></button>
                                </div>
                                <div class="modal-body">
                                    Anda yakin ingin menghapus ?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ url('/transaksi/hapus/'.$saleTransaction->sale_transaction_id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                    <button id="buttonCloseModalDelete" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var btnModal = document.getElementById('btnImage');
    var modal = document.getElementById('exampleModal');
    btnModal.onclick = function() {
        modal.style.display = 'block';
        var iconCloseModal = document.getElementById('iconCloseModal');
        iconCloseModal.onclick = function() {
            modal.style.display = 'none';
        }
        var buttonCloseModal = document.getElementById('buttonCloseModal');
        buttonCloseModal.onclick = function() {
            modal.style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target == modal) {
                modal.style.display = "none";
            }
        }
    }
    var btnModalDelete = document.getElementById('btnModalDelete');
    var modalDelete = document.getElementById('modalDelete');
    btnModalDelete.onclick = function() {
        modalDelete.style.display = 'block';
        var iconCloseModalDelete = document.getElementById('iconCloseModalDelete');
        iconCloseModalDelete.onclick = function() {
            modalDelete.style.display = 'none';
        }
        var buttonCloseModalDelete = document.getElementById('buttonCloseModalDelete');
        buttonCloseModalDelete.onclick = function() {
            modalDelete.style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target == modalDelete) {
                modalDelete.style.display = "none";
            }
        }
    }
</script>
@endsection



