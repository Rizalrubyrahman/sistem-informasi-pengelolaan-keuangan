@extends('layouts.app')
@section('title','Tambah Piutang')
@section('content')
<div class="card shadow" style="border-radius: 15px">
    <div class="card-body">
        <h1 class="h3 mb-3"><strong>Tambah Piutang</strong> </h1>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ url('/hutang/tambah/') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="debt_amount">Nominal</label>
                        <input type="text" name="debt_amount" id="debt_amount"  class="form-control @error('debt_amount') is-invalid @enderror">
                        @error('debt_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="customer_name">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="customer_name"  class="form-control @error('customer_name') is-invalid @enderror">
                        @error('customer_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="telp">No Telp</label>
                        <input type="text" name="telp" id="telp"  class="form-control @error('telp') is-invalid @enderror">
                        @error('telp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="debt_date">Tanggal</label>
                        <input type="date" name="debt_date" id="debt_date"  class="form-control @error('debt_date') is-invalid @enderror">
                        @error('debt_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="due_date">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date" readonly  class="form-control @error('due_date') is-invalid @enderror">
                        @error('due_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mt-2">
                        <label for="note">Keterangan</label>
                        <input type="text" name="note" id="note" class="form-control @error('note') is-invalid @enderror">
                        @error('note')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('hutang') }}"  style="border-color: #1cbb8c" class="btn">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
