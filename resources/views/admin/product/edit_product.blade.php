@extends('layouts.app')
@section('title','Ubah Produk')
@section('content')
<div class="card shadow">
    <div class="card-body">
        <h1 class="h3 mb-3"><strong>Ubah Produk</strong> </h1>
        <div class="row">
            <div class="col-md-6 mt-4">
                <form action="{{ url('/produk/ubah/'.$product->product_id) }}" method="POST">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="product_name">Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" value="{{ old('product_name',$product->product_name) }}" class="form-control @error('product_name') is-invalid @enderror">
                        @error('product_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="product_price">Harga Jual</label>
                        <input type="text"  name="product_price" id="product_price" value="{{ old('product_price',$product->product_price) }}" class="form-control @error('product_price') is-invalid @enderror"/>
                        @error('product_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- <div class="form-group mt-2">
                        <label for="qty">Stok</label>
                        <input type="number" min="0"  name="qty" id="qty" value="{{ old('qty',$product->qty) }}" class="form-control"/>
                    </div> --}}
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-warning">Ubah</button>
                        <a href="{{ url('produk') }}" style="border-color: #1cbb8c" class="btn">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        var productPrice = document.getElementById('product_price');
        productPrice.value = formatRupiah(productPrice.value , 'Rp ');

        productPrice.addEventListener('keyup', function(e)
        {
            productPrice.value = formatRupiah(this.value, 'Rp ');
        });

        /* Fungsi */
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
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
    </script>
@endsection

