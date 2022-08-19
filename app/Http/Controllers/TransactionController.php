<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SaleTransactionExport;
use App\Product;
use App\SaleTransaction;
use App\SaleTransactionProduct;
use App\SaleChannel;
use App\PaymentMethod;
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
    public function transactionEdit($transactionId)
    {

        $saleTransaction = SaleTransaction::where('sale_transaction_id',$transactionId)->first();
        $saleProducts = SaleTransactionProduct::where('sale_transaction_id',$transactionId)->get();
        $saleChannels = SaleChannel::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();

        return view('admin.transaction.sale.edit_transaction',compact(['saleTransaction','saleChannels','paymentMethods','products','saleProducts']));

    }
    public function transactionInput()
    {
        $saleChannels = SaleChannel::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();
        return view('admin.transaction.sale.input_transaction',compact(['saleChannels','paymentMethods','products']));

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
    public function transactionUpdate($transactionId,Request $request)
    {
        $saleAmount = filter_var($request->sale_amount, FILTER_SANITIZE_NUMBER_INT);
        $expenseAmount = filter_var($request->expense_amount, FILTER_SANITIZE_NUMBER_INT);
        $imageBukti  = $request->file('bukti_pembayaran');
        $messages = [
            'date.required' => 'Tanggal harus diisi.',
        ];
        $validate = Validator::make($request->all(),[
            'date' => 'required',
            'bukti_pembayaran' => 'mimes:jpg,png,jpeg'
        ],$messages);
        if($validate->passes())
        {

            if ($request->hasFile('bukti_pembayaran')) {
                $images_name  = time().'.'.$imageBukti->getClientOriginalExtension();
            $prefix_name = 'bukti_pembayaran_';
            $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/admin/images/bukti_pembayaran';
                $imageBukti->move($destinationPath, $prefix_name.$images_name);
                $buktiPembayaran = $prefix_name.$images_name;
            }else{
                $buktiPembayaran = null;
            }

            $updateSaleTransaction = SaleTransaction::find($transactionId);
            $updateSaleTransaction->payment_method_id = $request->payment_method;
            $updateSaleTransaction->sale_channel_id = $request->sale_channel;
            $updateSaleTransaction->date = $request->date;
            $updateSaleTransaction->sale_amount =  $saleAmount;
            $updateSaleTransaction->expense_amount = $expenseAmount;
            $updateSaleTransaction->note = $request->note;
            $updateSaleTransaction->file = $buktiPembayaran;
            $updateSaleTransaction->updated_at = Carbon::now();
            $updateSaleTransaction->save();

            $saleProducts = SaleTransactionProduct::where('sale_transaction_id',$transactionId)->get();

            foreach($request->sale_produk_id as $keySaleTransaction => $saleTransactionId){
                // dd(SaleTransactionProduct::where('sale_transaction_id',$transactionId)->where('sale_transaction_product_id','!=',$saleTransactionId)->get());
                if($saleTransactionId != null){
                    foreach($saleProducts as $saleProduct){
                        if($saleProduct->sale_transaction_product_id == $saleTransactionId){
                            // $updateProducts = Product::find($request->produk_id[$saleTransactionId]);
                            // if($request->qty[$saleTransactionId] > $updateProducts->qty){
                            //     Alert::error('Gagal', 'Qty tidak boleh melebihi stok produk.');
                            //     return redirect()->back();
                            // }else{
                            //     $updateProducts->qty = ($updateProducts->qty - $request->qty[$saleTransactionId]);
                            //     $updateProducts->save();
                            // }

                            $updateTransactionProduct = SaleTransactionProduct::find($saleTransactionId);
                            $updateTransactionProduct->product_id = $request->produk_id[$saleTransactionId];
                            $updateTransactionProduct->qty = $request->qty[$saleTransactionId];
                            $updateTransactionProduct->save();
                        }else{
                            $getProducts = SaleTransactionProduct::where('sale_transaction_id',$transactionId)->whereNotIn('sale_transaction_product_id',$request->sale_produk_id )->get();
                            if(count($getProducts) != 0){
                                foreach($getProducts as $product){
                                    $deleteProduct = SaleTransactionProduct::find($product->sale_transaction_product_id);
                                    $deleteProduct->delete();
                                }
                            }
                        }
                    }
                }else{
                    if($request->produk_id[$keySaleTransaction] != null){
                        // $updateProduct = Product::find($request->produk_id[$keySaleTransaction]);
                        // if($request->qty[$keySaleTransaction] > $updateProduct->qty){
                        //     Alert::error('Gagal', 'Qty tidak boleh melebihi stok produk.');
                        //     return redirect()->back();
                        // }else{
                        //     $updateProduct->qty = ($updateProduct->qty - $request->qty[$keySaleTransaction]);
                        //     $updateProduct->save();
                        // }

                        $newTransactionProduct = new SaleTransactionProduct;
                        $newTransactionProduct->product_id = $request->produk_id[$keySaleTransaction];
                        $newTransactionProduct->qty = $request->qty[$keySaleTransaction];
                        $newTransactionProduct->sale_transaction_id = $transactionId;
                        $newTransactionProduct->save();
                    }

                }
            }


            Alert::success('Berhasil', 'Transaksi berhasil diubah.');
            return redirect()->back();
        }
        Alert::error('Gagal', 'Transaksi gagal diubah.');
        return redirect()->back()->withErrors($validate)->withInput();

    }

    public function transactionStore(Request $request){
        $saleAmount = filter_var($request->sale_amount, FILTER_SANITIZE_NUMBER_INT);
        $expenseAmount = filter_var($request->expense_amount, FILTER_SANITIZE_NUMBER_INT);
        $imageBukti  = $request->file('bukti_pembayaran');
        $messages = [
            'date.required' => 'Tanggal harus diisi.',
        ];
        $validate = Validator::make($request->all(),[
            'date' => 'required',
            'bukti_pembayaran' => 'mimes:jpg,png,jpeg'
        ],$messages);
        if($validate->passes())
        {

            if ($request->hasFile('bukti_pembayaran')) {
                $images_name  = time().'.'.$imageBukti->getClientOriginalExtension();
                $prefix_name = 'bukti_pembayaran_';
                $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/admin/images/bukti_pembayaran';
                $imageBukti->move($destinationPath, $prefix_name.$images_name);
                $buktiPembayaran = $prefix_name.$images_name;
            }else{
                $buktiPembayaran = null;
            }

                $newSaleTransaction = new SaleTransaction;
                $newSaleTransaction->payment_method_id = $request->payment_method;
                $newSaleTransaction->sale_channel_id = $request->sale_channel;
                $newSaleTransaction->date = $request->date;
                $newSaleTransaction->sale_amount =  $saleAmount;
                $newSaleTransaction->expense_amount = $expenseAmount;
                $newSaleTransaction->file = $buktiPembayaran;
                $newSaleTransaction->note = $request->note;
                $newSaleTransaction->save();

            foreach($request->produk_id as $keyProduct => $productId){
                if($productId != null){

                    // $updateProduct = Product::find($productId);
                    // if($request->qty[$keyProduct] > $updateProduct->qty){
                    //     Alert::error('Gagal', 'Qty tidak boleh melebihi stok produk.');
                    //     return redirect()->back();
                    // }else{
                    //     $updateProduct->qty = ($updateProduct->qty - $request->qty[$keyProduct]);
                    //     $updateProduct->save();
                    // }

                    $newTransactionProduct = new SaleTransactionProduct;
                    $newTransactionProduct->product_id = $productId;
                    $newTransactionProduct->qty = $request->qty[$keyProduct];
                    $newTransactionProduct->sale_transaction_id = $newSaleTransaction->sale_transaction_id;
                    $newTransactionProduct->save();
                }
            }

            Alert::success('Berhasil', 'Transaksi berhasil disimpan.');
            return redirect('transaksi');
        }
        Alert::error('Gagal', 'Transaksi gagal disimpan.');
        return redirect()->back()->withErrors($validate)->withInput();
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
        $saleProducts = SaleTransactionProduct::where('sale_transaction_id',$transactionId)->get();
        foreach ($saleProducts as $saleProduct) {
            $deleteTransactionProduct = SaleTransactionProduct::find($saleProduct->sale_transaction_product_id);
            $deleteTransactionProduct->delete();
        }
        $deleteTransaction = SaleTransaction::find($transactionId);
        $deleteTransaction->delete();

        Alert::success('Berhasil', 'Transaksi berhasil dihapus.');
        return redirect('transaksi');
    }
}
