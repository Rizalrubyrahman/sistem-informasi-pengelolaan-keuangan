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
