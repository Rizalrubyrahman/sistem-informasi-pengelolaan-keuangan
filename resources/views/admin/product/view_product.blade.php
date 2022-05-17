@extends('layouts.app')
@section('title','Stok Barang')
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
<h1 class="h3 mb-3"><strong>Stok Barang</strong> </h1>
<div class="card shadow" style="border-radius: 15px">
    <div class="card-body">

        <div class="row">
            <div class="col-md-6 mt-3">
                <a href="{{ url('/stok_barang/tambah') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i> Tambah Stok Barang</a>
            </div>
            <div class="col-md-6 mt-4">
                <div class="d-flex justify-content-end">
                    <div class="input-group" >
                        <input type="text" class="form-control" onkeyup="showIconClear(this)" style="border-right:0px;border-color: #1cbb8c" placeholder="Cari barang" aria-label="Cari barang" id="cari_barang" aria-describedby="button-addon2">
                        <button class="btn" style="border-left:0px;border-color: #1cbb8c" type="button" id="btnClear"><span id="clear" style="display: none;"><i class="fa-solid fa-xmark"></i></span></button>
                    </div>
                    <div>&nbsp;</div>
                    <select name="filter" id="filter"  class="form-select" style="border-color: #1cbb8c;width:180px;">
                        <option>Filter</option>
                        <option value="1">A - Z</option>
                        <option value="2">Z - A</option>
                        <option value="3">Stok tersedikit</option>
                        <option value="4">Stok terbanyak</option>
                    </select>
                </div>
            </div>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <td align="center">No</td>
                    <td>Nama Barang</td>
                    <td align="center">Harga</td>
                    <td align="center">Stok</td>
                    <td align="center">Opsi</td>
                </tr>
            </thead>

            <tbody>
               @forelse ($products as $product)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td align="center">Rp {{ number_format($product->product_price, 0, ",", ".") }}</td>
                        <td align="center">{{ $product->qty }}</td>
                        <td align="center">
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
                        </td>
                    </tr>
               @empty
                    <td colspan="5">Tidak ada produk</td>
               @endforelse


            </tbody>
        </table>

        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
        {{ $products->links() }}
        </nav>
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

        $('#cari_barang').on('keyup',function(){
            $value=$(this).val();
            $.ajax({
                    type : 'get',
                    url : '{{url("/stok_barang/cari")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('tbody').html(data);
                    }
            });

        })
        $('#filter').on('change',function(){
            $value=$(this).val();
            console.log($value);
            $.ajax({
                    type : 'get',
                    url : '{{url("/stok_barang/filter")}}',
                    data:{'search':$value},
                    success:function(data){
                        $('tbody').html(data);
                    }
            });

        })
        $('#btnClear').on('click',function(){
                $value = $('#cari_barang').val('');
                $("#clear").css("display", "none");
                $.ajax({
                    type : 'get',
                    url : '{{url("/stok_barang/cari")}}',
                    data:{'search':null},
                    success:function(data){
                        $('tbody').html(data);
                    }
            });
        });
    </script>

@endsection
