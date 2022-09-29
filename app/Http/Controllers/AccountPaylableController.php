<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountPaylable;
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

        $accountPaylables = AccountPaylable::orderBy('debt_date')->paginate(10);
        return view('admin.account_paylable.view_account_paylable',compact(['accountPaylables']));
    }
    public function debtInput()
    {

        return view('admin.account_paylable.sale.input_account_paylable',compact(['saleChannels','paymentMethods','products']));

    }
}
