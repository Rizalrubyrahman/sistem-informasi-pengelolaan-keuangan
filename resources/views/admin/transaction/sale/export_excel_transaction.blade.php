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
                $dateNow = \Carbon\Carbon::now()->format('Y-m-d');
                $jamNow = \Carbon\Carbon::now()->format('h:i a');
@endphp
<table>
    <tr>
        <th><strong>Restu Ibu</strong></th>
    </tr>
    <tr>
        <th><strong>Tel. 08993171565</strong></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th><strong>Tanggal Laporan: </strong></th>
        <th><strong>{{ tgl_indo($fromDate) }} - {{ tgl_indo($toDate) }}</strong></th>
    </tr>
    <tr>
        <th><strong>Dibuat Pada:</strong></th>
        <th><strong>{{ tgl_indo($dateNow) }} {{ $jamNow }}</strong></th>
        <th></th>
        <th></th>
        <th><strong>Total Transaksi: {{ count($saleTransactions) }}</strong></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th align="center"><strong>Tanggal</strong></th>
        <th align="center"><strong>Deskripsi</strong></th>
        <th align="center"><strong>Penjualan</strong></th>
        <th align="center"><strong>Pengeluaran</strong></th>
        <th align="center"><strong>Keuntungan</strong></th>
    </tr>
    @foreach ($saleTransactions as $saleTransaction)
    @php
        $totalSale[] = $saleTransaction->sale_amount;
        $totalExpense[] = $saleTransaction->expense_amount;
        $totalProfit[] = $saleTransaction->sale_amount - $saleTransaction->expense_amount;
    @endphp
        <tr>
            <th align="center"><strong>{{ tgl_indo($saleTransaction->date) }}</strong></th>
            <th align="center"><strong>{{ ($saleTransaction->note == null) ? '-' : $saleTransaction->note }}</strong></th>
            <th align="center"><strong>Rp {{ number_format($saleTransaction->sale_amount, 0, ",", ".") }}</strong></th>
            <th align="center"><strong>Rp {{ number_format($saleTransaction->expense_amount, 0, ",", ".") }}</strong></th>
            <th align="center"><strong>Rp {{ number_format(($saleTransaction->sale_amount-$saleTransaction->expense_amount), 0, ",", ".") }}</strong></th>
        </tr>
    @endforeach
    <tr>
        <th align="center" colspan="2"><strong>Total</strong></th>
        <th align="center"><strong>Rp {{ number_format(array_sum($totalSale), 0, ",", ".") }}</strong></th>
        <th align="center"><strong>Rp {{ number_format(array_sum($totalExpense), 0, ",", ".") }}</strong></th>
        <th align="center"><strong>Rp {{ number_format(array_sum($totalProfit), 0, ",", ".") }}</strong></th>
    </tr>
</table>
