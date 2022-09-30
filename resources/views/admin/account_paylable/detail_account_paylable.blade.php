@extends('layouts.app')
@section('title','Detail Piutang')
@section('content')
@php
    function tgl_indo($tanggal){
                    $bulan = array (
                        1 =>   'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    );
                    $pecahkan = explode('-', $tanggal);

                    // variabel pecahkan 0 = tanggal
                    // variabel pecahkan 1 = bulan
                    // variabel pecahkan 2 = tahun

                    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
                }
                if (count($accountPaylableDetails) == 0) {
                    $totalBayar[]=0;
                } else {
                    foreach ($accountPaylableDetails as $apd){
                        $totalBayar[] = $apd->pay_amount;
                    }
                }
@endphp
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
<div class="card shadow" style="border-radius: 15px">
    <div class="card-body">
        <h1 class="h3 mb-3"><strong>Detail Piutang</strong> </h1>
        <div class="row">
            <div class="col-md-6">
                <div style="border: 2px solid #dee2e6; padding:20px;border-radius:5px 5px 0px 0px">
                    <span>Total Hutang <span style="background-color: #eef2f5; padding:5px; border-radius:3px;"><strong>{{ $accountPaylable->customer_name }}</strong></span> Ke <span style="background-color: #eef2f5; padding:5px; border-radius:3px;"><strong>Saya</strong></span></span>

                    <div class="row" style="margin-top:30px">
                        <div class="col-md-2">
                            <span style="background-color: #dc4e4d;color:white; padding:2px 5px 2px 5px; border-radius:3px;font-size:10pt">Rp</span>
                        </div>
                        <div class="col-md-4" style="margin-top:-5px">
                            <span style="font-size: 16pt;margin-left:-43px;"><strong>{{ number_format($accountPaylable->debt - array_sum($totalBayar), 0, ",", ".") }}</strong></span>
                        </div>
                        <div class="col-md-6" style="margin-top: -4px;">
                            <form action="{{ url('/hutang/tandai_lunas/'.$accountPaylable->account_paylable_id) }}" method="POST">
                                @csrf
                                @if (array_sum($totalBayar) >= $accountPaylable->debt)
                                <button type="button" class="btn btn-sm" style="border-color: #2ab284;border-radius:3px;margin-left:68px;color:#2ab284;width:130px"><i class="fa fa-check" aria-hidden="true" disabled></i> Sudah Lunas </button>
                                @else
                                <button type="submit" class="btn btn-sm" style="border-color: #2ab284;border-radius:3px;margin-left:68px;color:#2ab284;width:130px"><i class="fa fa-check" aria-hidden="true"></i> Tandai Lunas </button>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>
                @if (array_sum($totalBayar) < $accountPaylable->debt)
                <div style="border: 2px solid #dee2e6; padding:5px 20px 5px 20px;border-radius:0px 0px 5px 5px; border-top:none">
                    <div class="row">
                        <div class="col-md-10">
                            @if ($accountPaylable->due_date == null)
                                Atur jatuh tempo untuk memasang pengingat
                            @else
                            <i class="fa fa-calendar" style="color: #2ab284" aria-hidden="true"></i> Jatuh Tempo <span style="color: #dc4e4d">{{ tgl_indo($accountPaylable->due_date) }}</span>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if ($accountPaylable->due_date == null)
                                <a style="text-decoration: underline;color:#2ab284" id="btnModalAtur">Atur</a>
                                <div class="modal" id="modalAtur" style="padding-top: 4%">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3><strong>Atur Tanggal Jatuh Tempo</strong></h3>
                                                <button id="iconCloseModalAtur" type="button" class="btn-close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <form action="{{ url('/hutang/atur_tanggal/'.$accountPaylable->account_paylable_id) }}" method="post">
                                                        @csrf
                                                    <label>Tanggal Jatuh Tempo</label>
                                                    <input type="date" name="due_date" value="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                </form>
                                                <button id="buttonCloseModalAtur" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a style="text-decoration: underline;color:#2ab284" id="btnModalDelete">Ubah</a>
                                <div class="modal" id="modalDelete" style="padding-top: 4%">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3><strong>Ubah Tanggal Jatuh Tempo</strong></h3>
                                                <button id="iconCloseModalDelete" type="button" class="btn-close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <form action="{{ url('/hutang/atur_tanggal/'.$accountPaylable->account_paylable_id) }}" method="post">
                                                        @csrf
                                                    <label>Tanggal Jatuh Tempo</label>
                                                    <input type="date" name="due_date" value="{{ $accountPaylable->due_date }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">


                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </form>
                                                <button id="buttonCloseModalDelete" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <table class="table" style="margin-top:10px">
                    <thead>
                        <tr>
                            <th style="text-align: center">Tanggal Bayar</th>
                            <th style="text-align: center">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accountPaylableDetails as $apd)

                        <tr>
                            <td style="text-align: center">{{ tgl_indo($apd->pay_date) }}</td>
                            <td style="text-align: center" ><span style="color: #1cbb8c">Rp {{ number_format($apd->pay_amount, 0, ",", ".") }}</span></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group" style="margin-top:50px;width:100px">
            <div class="row">
                <div class="col-md-8">
                    @if (array_sum($totalBayar) < $accountPaylable->debt)
                    <button class="btn" style="border-color: #1cbb8c;background-color:#1cbb8c;color:white" id="btnModalBayar">Bayar</button>
                    @endif

                    <div class="modal" id="modalBayar" style="padding-top: 4%">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3><strong>Bayar</strong></h3>
                                    <button id="iconCloseModalBayar" type="button" class="btn-close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/hutang/bayar/'.$accountPaylable->account_paylable_id) }}" method="post">
                                            @csrf
                                            <div class="form-group">

                                                <label>Nominal Bayar</label>
                                                <input type="text" name="pay_amount" id="pay_amount" value="{{ $accountPaylable->pay_amount }}" class="form-control">
                                            </div>
                                    <div class="form-group mt-2">

                                        <label>Tanggal Bayar</label>
                                        <input type="date" name="pay_date"  class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">


                                        <button type="submit" class="btn btn-primary" style="border-color: #1cbb8c;background-color:#1cbb8c;color:white" id="btnModalBayar">Bayar</button>
                                    </form>
                                    <button id="buttonCloseModalBayar" type="button" class="btn" style="border-color: #1cbb8c;margin-left:10px">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (array_sum($totalBayar) < $accountPaylable->debt)
                <div class="col-md-4">
                    <a style="border: 1px solid #dee2e6;" href="{{ url('hutang') }}" class="btn">Kembali</a>
                </div>
                @else
                <div class="col-md-4" style="margin-left: -86px">
                    <a style="border: 1px solid #dee2e6;" href="{{ url('hutang') }}" class="btn">Kembali</a>
                </div>
                @endif
            </div>


        </div>
    </div>
</div>
<script>
    var btnModalAtur = document.getElementById('btnModalAtur');
var modalAtur = document.getElementById('modalAtur');
    btnModalAtur.onclick = function() {
        modalAtur.style.display = 'block';
        var iconCloseModalAtur = document.getElementById('iconCloseModalAtur');
        iconCloseModalAtur.onclick = function() {
            modalAtur.style.display = 'none';
        }
        var buttonCloseModalAtur = document.getElementById('buttonCloseModalAtur');
        buttonCloseModalAtur.onclick = function() {
            modalAtur.style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target == modalAtur) {
                modalAtur.style.display = "none";
            }
        }
    }
</script>
<script>
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
<script>

    var btnModalBayar = document.getElementById('btnModalBayar');
var modalBayar = document.getElementById('modalBayar');
    btnModalBayar.onclick = function() {
        var pay_amount = document.getElementById('pay_amount');
    pay_amount.addEventListener('keyup', function(e)
    {
        pay_amount.value = formatRupiah(this.value, 'Rp ');
    });
        modalBayar.style.display = 'block';
        var iconCloseModalBayar = document.getElementById('iconCloseModalBayar');
        iconCloseModalBayar.onclick = function() {
            modalBayar.style.display = 'none';
        }
        var buttonCloseModalBayar = document.getElementById('buttonCloseModalBayar');
        buttonCloseModalBayar.onclick = function() {
            modalBayar.style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target == modalBayar) {
                modalBayar.style.display = "none";
            }
        }
    }

</script>
<script>
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

    var debtAmount = document.getElementById('debt_amount');
    debtAmount.addEventListener('keyup', function(e)
    {
        debtAmount.value = formatRupiah(this.value, 'Rp ');
    });

    document.querySelector("#debt_date").addEventListener("change", function() {
  var invoiceDate = document.querySelector("#debt_date").value;
  var dueDateElement = document.querySelector("#due_date");

  if (invoiceDate.length) {
    invoiceDate = invoiceDate.split("-");
    invoiceDate = new Date(invoiceDate[0], invoiceDate[1] - 1, invoiceDate[2]);
    invoiceDate.setDate(invoiceDate.getDate() + 31);
    dueDateElement.valueAsDate = null;
    dueDateElement.valueAsDate = invoiceDate;
    //console.log(invoiceDate, dueDateElement.value);
  }
});

</script>
@endsection
