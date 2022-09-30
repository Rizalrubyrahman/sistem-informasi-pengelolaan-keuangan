<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountPaylable;
use App\AccountPaylableDetail;
use Alert,Validator,PDF;
use Carbon\Carbon;

class AccountPaylableController extends Controller
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
    public function debtView()
    {
        $accountPaylables = AccountPaylable::orderBy('debt_date','DESC')->paginate(10);
        $accountPaylableDetails = AccountPaylableDetail::all();
        foreach($accountPaylables as $ap){
            foreach($accountPaylableDetails as $apd){
                $totalBayar[$ap->account_paylable_id][] = ($ap->account_paylable_id == $apd->account_paylable_id) ? $apd->pay_amount : 0;

            }
        }
        foreach($accountPaylableDetails as $apd){
            $allTotalBayar[] = $apd->pay_amount;
        }
        return view('admin.account_paylable.view_account_paylable',compact(['accountPaylables','totalBayar','accountPaylableDetails','allTotalBayar']));
    }
    public function debtInput()
    {
        return view('admin.account_paylable.input_account_paylable',compact([]));

    }
    public function debtStore(Request $request){
        $debtAmount = filter_var($request->debt_amount, FILTER_SANITIZE_NUMBER_INT);
        $messages = [
            'debt_date.required' => 'Tanggal harus diisi.',
            'debt_amount.required' => 'Nominal harus diisi.',
            'customer_name.required' => 'Nama Pelanggan harus diisi.',
        ];
        $validate = Validator::make($request->all(),[
            'debt_date' => 'required',
            'debt_amount' => 'required',
            'customer_name' => 'required',
        ],$messages);
        if($validate->passes())
        {
                $newAccountPaylable = new AccountPaylable;
                $newAccountPaylable->debt = $debtAmount;
                $newAccountPaylable->pay = null;
                $newAccountPaylable->customer_name = $request->customer_name;
                $newAccountPaylable->customer_telp =  $request->telp;
                $newAccountPaylable->debt_date = $request->debt_date;
                $newAccountPaylable->due_date = $request->due_date;
                $newAccountPaylable->note = $request->note;
                $newAccountPaylable->status = 'Belum Lunas';
                $newAccountPaylable->save();


            Alert::success('Berhasil', 'Piutang berhasil disimpan.');
            return redirect('hutang');
        }
        Alert::error('Gagal', 'Piutang gagal disimpan.');
        return redirect()->back()->withErrors($validate)->withInput();
    }
    public function debtSearch(Request $request)
    {
        if ($request->search == null) {
            $accountPaylables = AccountPaylable::orderBy('debt_date','DESC')->get();
        } else {
            $accountPaylables=AccountPaylable::where('customer_name','LIKE','%'.$request->search."%")->get();
        }
        $accountPaylableDetails = AccountPaylableDetail::all();
        foreach($accountPaylables as $ap){
            foreach($accountPaylableDetails as $apd){
                $totalBayar[$ap->account_paylable_id][] = ($ap->account_paylable_id == $apd->account_paylable_id) ? $apd->pay_amount : 0;

            }
        }
        foreach($accountPaylableDetails as $apd){
            $allTotalBayar[] = $apd->pay_amount;
        }

        return view('admin.account_paylable.list_piutang',compact(['accountPaylables','totalBayar','accountPaylableDetails','allTotalBayar']));

    }
    public function debtSortBy(Request $request)
    {
        if($request->search == 1){
            $accountPaylables = AccountPaylable::orderBy('debt_date','DESC')->paginate(10);
        }else if($request->search == 2){
            $accountPaylables = AccountPaylable::orderBy('debt_date','ASC')->paginate(10);

        }else {
            $accountPaylables = AccountPaylable::orderBy('debt_date','DESC')->paginate(10);
        }
        $accountPaylableDetails = AccountPaylableDetail::all();
        foreach($accountPaylables as $ap){
            foreach($accountPaylableDetails as $apd){
                $totalBayar[$ap->account_paylable_id][] = ($ap->account_paylable_id == $apd->account_paylable_id) ? $apd->pay_amount : 0;

            }
        }
        foreach($accountPaylableDetails as $apd){
            $allTotalBayar[] = $apd->pay_amount;
        }
        return view('admin.account_paylable.list_piutang',compact(['accountPaylables','totalBayar','accountPaylableDetails','allTotalBayar']));

    }
    public function debtSortByDate(Request $request)
    {
        $debtDate = Carbon::parse($request->debtDate)->format('Y-m-d');
        if($request->debtDate != null){
            $accountPaylables = AccountPaylable::orderBy('debt_date','Desc')->where('debt_date',$debtDate)->get();
        }else {
            $accountPaylables = AccountPaylable::orderBy('debt_date','Desc')->get();
        }
        $accountPaylableDetails = AccountPaylableDetail::all();
        foreach($accountPaylables as $ap){
            foreach($accountPaylableDetails as $apd){
                $totalBayar[$ap->account_paylable_id][] = ($ap->account_paylable_id == $apd->account_paylable_id) ? $apd->pay_amount : 0;

            }
        }
        foreach($accountPaylableDetails as $apd){
            $allTotalBayar[] = $apd->pay_amount;
        }
        return view('admin.account_paylable.list_piutang',compact(['accountPaylables','totalBayar','accountPaylableDetails','allTotalBayar']));

    }

    public function debtDetail($apId){
        $accountPaylable = AccountPaylable::find($apId);
        $accountPaylableDetails = AccountPaylableDetail::where('account_paylable_id',$apId)->get();
        return view('admin.account_paylable.detail_account_paylable',compact(['accountPaylable','accountPaylableDetails']));
    }
    public function debtTandaiLunas($apId)
    {
        $accountPaylable = AccountPaylable::find($apId);
        $accountPaylable->status = 'Lunas';
        $accountPaylable->save();

        $newAccountPaylableDetail = new AccountPaylableDetail;
        $newAccountPaylableDetail->account_paylable_id = $apId;
        $newAccountPaylableDetail->pay_amount = $accountPaylable->debt;
        $newAccountPaylableDetail->pay_date = Carbon::now();
        $newAccountPaylableDetail->save();
        Alert::success('Berhasil', 'Piutang Telah Lunas.');
        return redirect('hutang');
    }
    public function debtAturTanggal($apId,Request $request)
    {
        $accountPaylable = AccountPaylable::find($apId);
        $accountPaylable->due_date = $request->due_date;
        $accountPaylable->save();
        Alert::success('Berhasil', 'Tanggal Jatuh tempo telah diubah.');
        return redirect()->back();
    }

    public function debtPay($apId,Request $request)
    {
        $payAmount = filter_var($request->pay_amount, FILTER_SANITIZE_NUMBER_INT);
        $newAccountPaylableDetail = new AccountPaylableDetail;
        $newAccountPaylableDetail->account_paylable_id = $apId;
        $newAccountPaylableDetail->pay_amount = $payAmount;
        $newAccountPaylableDetail->pay_date = $request->pay_date;
        $newAccountPaylableDetail->save();
        Alert::success('Berhasil', 'Catatan hutang berhasil dibuat.');
        return redirect()->back();
    }
}
