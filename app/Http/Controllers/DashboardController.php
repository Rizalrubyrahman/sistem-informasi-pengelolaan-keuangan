<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SaleTransactionProduct;
use App\SaleTransaction;
use App\Product;
use Carbon\Carbon;
use DB;
class DashboardController extends Controller
{
       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $saleTransactionProducts = SaleTransactionProduct::all();
        $dataProducts = SaleTransactionProduct::join('product','product.product_id','sale_transaction_product.product_id')
                                            ->select('sale_transaction_product.product_id','product.product_name')
                                            ->get();
        foreach($saleTransactionProducts as $saleTransactionProduct){
            $jumlahPenjualanProduct[$saleTransactionProduct->product_id][] = $saleTransactionProduct->qty;
        }
        foreach($dataProducts as $dataProduct){
            $products[$dataProduct->product_id] = $dataProduct->product_name;
        }
        $saleTransaction = SaleTransaction::all();
        foreach($saleTransaction as $st){

        }
        for($i = 1; $i < 13; $i++){
            $saleTransaction = collect(DB::SELECT("SELECT sum(sale_amount) AS sale from sale_transactions where month(date)='$i'"))->first();

            $expenseTransaction = collect(DB::SELECT("SELECT sum(expense_amount) AS expense from sale_transactions where month(date)='$i'"))->first();

            $dataSale[$i] = ($saleTransaction == null) ? 0 : $saleTransaction->sale;
            $dataPengeluaran[$i] = ($expenseTransaction == null) ? 0 : $expenseTransaction->expense;


        }
        for($x = 1; $x < 13; $x++){
            $dataTransaction[] = ($dataSale[$x] - $dataPengeluaran[$x]);
        }

        return view('admin.dashboard',compact(['products','jumlahPenjualanProduct','dataTransaction']));
    }
}
