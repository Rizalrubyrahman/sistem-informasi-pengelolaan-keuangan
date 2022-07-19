
@if ( $saleTransactions->count() > 0)
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
                    url : '{{url("transaksi/cari_list")}}',
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
                    url : '{{url("transaksi/cari_list")}}',
                    data:{'search':null},
                    success:function(data){
                        $('#list').html(data);
                    }
            });
        });
</script>
