<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\SaleTransaction;
use Alert,Validator;
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
        $saleTransactions = SaleTransaction::orderBy('date')->get();
        return view('admin.transaction.sale.view_transaction',compact(['saleTransactions']));
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
}
