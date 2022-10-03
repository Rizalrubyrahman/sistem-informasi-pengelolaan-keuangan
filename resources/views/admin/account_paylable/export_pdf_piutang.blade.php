<!DOCTYPE html>
<html>

<head>
    <title>Laporan Hutang Pelanggan </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style>
        body{
            font-family: 'Times New Roman', Times, serif;
        }
        .table1 tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

    </style>
    @php
        function tgl_indo($tanggal){
            $bulan = array (
                1 => 'Januari',
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
    $dateNow = \Carbon\Carbon::now()->format('Y-m-d');
    $jamNow = \Carbon\Carbon::now()->format('h:i a');
    @endphp

    <strong><h3>Laporan Hutang Pelanggan</h3></strong>
    <table style="width: 100%">
        <tr>
            <td>Tanggal Laporan: sepanjang waktu hingga {{ tgl_indo($dateNow) }}</td>
            <td></td>
        </tr>
        <tr>
            <td>Dibuat Pada: {{ tgl_indo($dateNow) }} {{ $jamNow }}</td>
        </tr>
    </table>
    <br>
    <table class="table1" style="width: 100%">
        <tr style="background-color: #DDDCDC; padding:5px;">
            <th style="background-color: #DDDCDC; padding:5px;" class="text-center">Tanggal</th>
            <th style="background-color: #DDDCDC; padding:5px;" class="text-center">Nama Pelanggan</th>
            <th style="background-color: #DDDCDC; padding:5px;" class="text-center">Hutang</th>
            <th style="background-color: #DDDCDC; padding:5px;" class="text-center">Bayar</th>
            <th style="background-color: #DDDCDC; padding:5px;" class="text-center">Sisa</th>
        </tr>
        @foreach ($accountPaylables as $ap)

        <tr style="padding:5px;">
            <td style="padding:5px;" align="center">{{ tgl_indo($ap->debt_date) }}</td>
            <td style="padding:5px;" align="center">{{ ($ap->customer_name == null) ? '-' : $ap->customer_name }}
            </td>
            <td style="padding:5px;" align="center"><span style="color:#dc4e4d">Rp {{ number_format(($ap->debt), 0, ",", ".") }}</span>
            </td>
            <td style="padding:5px;" align="center"><span style="color:#1cbb8c">Rp {{ number_format((array_sum($totalBayar[$ap->account_paylable_id])), 0, ",", ".") }}</span>
            </td>
            <td style="padding:5px;" align="center">

                @if (array_sum($totalBayar[$ap->account_paylable_id]) >= $ap->debt)
                <span style="color:#1cbb8c">Rp {{ number_format(($ap->debt - array_sum($totalBayar[$ap->account_paylable_id])), 0, ",", ".") }}</span>
                @else
                <span style="color:#dc4e4d">Rp {{ number_format(($ap->debt - array_sum($totalBayar[$ap->account_paylable_id])), 0, ",", ".") }}</span>
                @endif


            </td>
            {{-- <td style="padding:8px;" align="center">{{ ($saleTransaction->note == null) ? '-' : $saleTransaction->note }}
            </td>
            <td style="padding:8px;" align="center">Rp {{ number_format($saleTransaction->sale_amount, 0, ",", ".") }}</td>
            <td style="padding:8px;" align="center">Rp {{ number_format($saleTransaction->expense_amount, 0, ",", ".") }}
            </td>
            <td style="padding:8px;" align="center">
                @if (($saleTransaction->sale_amount-$saleTransaction->expense_amount) < 0)
                    <span style="color:#dc4e4d">Rp {{ number_format(($saleTransaction->sale_amount-$saleTransaction->expense_amount), 0, ",", ".") }}</span>
                @else
                    Rp {{ number_format(($saleTransaction->sale_amount-$saleTransaction->expense_amount), 0, ",", ".") }}
                @endif
            </td> --}}
        </tr>
        @endforeach
        {{-- <tr style="padding:8px;">
            <td style="padding:8px;" align="center" colspan="2">Total</td>
            <td style="padding:8px;" align="center">Rp {{ number_format(array_sum($totalSale), 0, ",", ".") }}</td>
            <td style="padding:8px;" align="center">Rp {{ number_format(array_sum($totalExpense), 0, ",", ".") }}</td>
            <td style="padding:8px;" align="center">
                @if (array_sum($totalProfit) < 0)
                    <span style="color:#dc4e4d">Rp {{ number_format(array_sum($totalProfit), 0, ",", ".") }}</span>
                @else
                    Rp {{ number_format(array_sum($totalProfit), 0, ",", ".") }}
                @endif
            </td>
        </tr> --}}
    </table>


</body>

</html>
