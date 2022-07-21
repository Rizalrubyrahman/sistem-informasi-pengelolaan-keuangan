<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SaleTransactionExport;
use App\Product;
use App\SaleTransaction;
use Alert,Validator,PDF;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function transactionView()
    {
        $startDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->lastOfMonth()->format('Y-m-d');
        $saleTransactions = SaleTransaction::orderBy('date')->whereBetween('date',[$startDate,$endDate])->get();
        return view('admin.transaction.sale.view_transaction',compact(['saleTransactions','startDate','endDate']));
    }
    public function transactionSearch(Request $request)
    {
        if ($request->search == null) {
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        } else {
            $saleTransactions = SaleTransaction::where('note','LIKE','%'.$request->search."%")->get();
        }


        return view('admin.transaction.sale.list_transaction',compact(['saleTransactions']));

    }
    public function transactionSearchList(Request $request)
    {
        if ($request->search == null) {
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        } else {
            $saleTransactions = SaleTransaction::where('note','LIKE','%'.$request->search."%")->get();
        }


        return view('admin.transaction.sale.list_transaction',compact(['saleTransactions']));

    }
    public function transactionFilter(Request $request)
    {
        $output="";
        if($request->search == 1){
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        }else if($request->search == 2){
            $saleTransactions = SaleTransaction::orderBy('date')->where('status','Lunas')->get();
        }else if($request->search == 3){
            $saleTransactions = SaleTransaction::orderBy('date')->where('status','Belum Lunas')->get();

        }else {
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        }
        return view('admin.transaction.sale.list_transaction',compact(['saleTransactions']));

    }
    public function transactionSortBy(Request $request)
    {
        $output="";
        if($request->search == 1){
            $saleTransactions = SaleTransaction::orderBy('date','ASC')->get();
        }else if($request->search == 2){
            $saleTransactions = SaleTransaction::orderBy('date','DESC')->get();
        }else {
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        }
        return view('admin.transaction.sale.list_transaction',compact(['saleTransactions']));

    }
    public function transactionDetail($transactionId)
    {

        $saleTransaction = SaleTransaction::where('sale_transaction_id',$transactionId)->first();

        return view('admin.transaction.sale.detail_transaction',compact(['saleTransaction']));

    }
    public function transactionSortByDate(Request $request)
    {
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        if($request->fromDate != null || $request->toDate != null){
            $saleTransactions = SaleTransaction::orderBy('date')->whereBetween('date',[$fromDate,$toDate])->get();
        }else {
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        }
        return view('admin.transaction.sale.list_transaction',compact(['saleTransactions']));

    }
    public function transactionSortByDateSum(Request $request)
    {
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        if($request->fromDate != null || $request->toDate != null){
            $saleTransactions = SaleTransaction::orderBy('date')->whereBetween('date',[$fromDate,$toDate])->get();
        }else {
            $saleTransactions = SaleTransaction::orderBy('date')->get();
        }
        return response()->json([
            'status' => true,
            'data'   => $saleTransactions,
        ]);

    }
    public function exportExcel(Request $request)
    {
        $tanggal = Carbon::now()->format('d-m-Y');
        return Excel::download(new SaleTransactionExport($request->fromDate,$request->toDate), 'Laporan Laba Rugi.'.$tanggal.'.xlsx');
    }
    public function ExportPDF(Request $request)
    {
        $tanggal = Carbon::now()->format('d-m-Y');
        $saleTransactions = SaleTransaction::orderBy('date')->whereBetween('date',[$request->fromDate,$request->toDate])->get();
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $pdf = PDF::loadView('admin.transaction.sale.export_pdf_transaction',compact(['saleTransactions','fromDate','toDate']));
        $fileName =  'Laporan Laba Rugi.'.$tanggal.'.pdf' ;
        $pdf->save($fileName);

        $pdf = public_path($fileName);
        return response()->download($pdf);
    }
    public function transactionDelete($transactionId)
    {
        $deleteTransaction = SaleTransaction::find($transactionId);
        $deleteTransaction->delete();

        Alert::success('Berhasil', 'Transaksi berhasil dihapus.');
        return redirect('transaksi');
    }
}
