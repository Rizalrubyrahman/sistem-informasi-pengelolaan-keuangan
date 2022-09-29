
                @if ( $accountPaylables->count() > 0)
                    @foreach ($accountPaylables as $ap)
                        <tr>
                            <td align="center">
                                    <span>{{ \Carbon\Carbon::parse($ap->debt_date)->format('d-m-Y') }}</span>
                            </td>
                            <td align="center">
                                <span>{{ Carbon\Carbon::parse($ap->due_date)->format('d-m-Y') }}</span>
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
                                @if ($ap->pay == 0 || $ap->pay == null)
                                    -
                                @else
                                    <span style="color:#2ab284">Rp {{ number_format($ap->pay, 0, ",", ".") }}</span>
                                @endif
                            </td>
                            <td align="center">
                                @php
                                    $payCustomer = ($ap->pay == null) ? 0 : $ap->pay;
                                @endphp

                                @if($ap->debt > $payCustomer)
                                    <span style="color:#dc4e4d">Rp {{ number_format(($ap->debt - $payCustomer), 0, ",", ".") }}</span>
                                @elseif($ap->debt == $ap->pay)
                                    0
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

