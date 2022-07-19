<!DOCTYPE html>
<html>

<head>
    <title>Laporan Laba Rugi </title>
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

    <strong><h3>Laporan Laba Rugi</h3></strong>
    <table style="width: 100%">
        <tr>
            <td>Tanggal Laporan: {{ tgl_indo($fromDate) }} - {{ tgl_indo($toDate) }}</td>
            <td></td>
        </tr>
        <tr>
            <td>Dibuat Pada: {{ tgl_indo($dateNow) }} {{ $jamNow }}</td>
            <td align="right">Total Transaksi: {{ count($saleTransactions) }}</td>
        </tr>
    </table>
    <br>
    <table class="table1" style="width: 100%">
        <tr style="background-color: #DDDCDC; padding:8px;">
            <th style="background-color: #DDDCDC; padding:8px;" align="center">Tanggal</th>
            <th style="background-color: #DDDCDC; padding:8px;" align="center">Deskripsi</th>
            <th style="background-color: #DDDCDC; padding:8px;" align="center">Penjualan</th>
            <th style="background-color: #DDDCDC; padding:8px;" align="center">Pengeluaran</th>
            <th style="background-color: #DDDCDC; padding:8px;" align="center">Keuntungan</th>
        </tr>
        @foreach ($saleTransactions as $saleTransaction)
        @php
        $totalSale[] = $saleTransaction->sale_amount;
        $totalExpense[] = $saleTransaction->expense_amount;
        $totalProfit[] = $saleTransaction->sale_amount - $saleTransaction->expense_amount;
        @endphp
        <tr style="padding:8px;">
            <td style="padding:8px;" align="center">{{ tgl_indo($saleTransaction->date) }}</td>
            <td style="padding:8px;" align="center">{{ ($saleTransaction->note == null) ? '-' : $saleTransaction->note }}
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
            </td>
        </tr>
        @endforeach
        <tr style="padding:8px;">
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
        </tr>
    </table>


</body>

</html>
