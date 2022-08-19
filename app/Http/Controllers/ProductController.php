<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Alert,Validator;
use Carbon\Carbon;


class ProductController extends Controller
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
    public function productView()
    {
        $products = Product::orderBy('product_name')->paginate(10);
        return view('admin.product.view_product',compact(['products']));
    }
    public function productInput()
    {
        return view('admin.product.input_product');
    }
    public function productEdit($productId)
    {
        $product = Product::where('product_id',$productId)->first();
        return view('admin.product.edit_product',compact(['product']));
    }
    public function productSearch(Request $request)
    {
        if ($request->search == null) {
            $products = Product::orderBy('product_name')->get();
        } else {
            $products=Product::where('product_name','LIKE','%'.$request->search."%")->get();
        }


        return view('admin.product.list_product',compact(['products']));

    }
    public function productSearchList(Request $request)
    {
        if ($request->search == null) {
            $products = Product::orderBy('product_name')->get();
        } else {
            $products=Product::where('product_name','LIKE','%'.$request->search."%")->get();
        }


        return view('admin.product.list_product',compact(['products']));

    }
    public function productFilter(Request $request)
    {
        $output="";
        if($request->search == 1){
            $products = Product::orderBy('product_name','ASC')->paginate(10);
        }else if($request->search == 2){
            $products = Product::orderBy('product_name','DESC')->paginate(10);

        }else {
            $products = Product::orderBy('product_name')->paginate(10);
        }
        return view('admin.product.list_product',compact(['products']));

    }
    public function productStore(Request $request)
    {
        $productPrice = filter_var($request->product_price, FILTER_SANITIZE_NUMBER_INT);
        $messages = [
            'product_name.required' => 'Nama produk harus diisi.',
            'product_price.required' => 'Harga jual harus diisi.'
        ];
        $validate = Validator::make($request->all(),[
            'product_name' => 'required',
            'product_price' => 'required'
        ],$messages);
        if($validate->passes())
        {
            $newProduct = new Product;
            $newProduct->product_name = $request->product_name;
            $newProduct->product_price = $productPrice;
            $newProduct->qty = null;
            $newProduct->created_at = Carbon::now();
            $newProduct->updated_at = NULL;
            $newProduct->save();

            Alert::success('Berhasil', 'Produk berhasil disimpan.');
            return redirect('produk');
        }
            Alert::error('Gagal', 'Produk gagal disimpan.');
            return redirect()->back()->withErrors($validate)->withInput();
    }
    public function productUpdate(Request $request,$productId)
    {
        $productPrice = filter_var($request->product_price, FILTER_SANITIZE_NUMBER_INT);
        $messages = [
            'product_name.required' => 'Nama produk harus diisi.',
            'product_price.required' => 'Harga jual harus diisi.'
        ];
        $validate = Validator::make($request->all(),[
            'product_name' => 'required',
            'product_price' => 'required'
        ],$messages);
        if($validate->passes())
        {
            $updateProduct = Product::find($productId);
            $updateProduct->product_name = $request->product_name;
            $updateProduct->product_price = $productPrice;
            $updateProduct->qty = null;
            $updateProduct->updated_at = Carbon::now();
            $updateProduct->save();

            Alert::success('Berhasil', 'Produk berhasil diubah.');
            return redirect('produk');
        }
            Alert::error('Gagal', 'Produk gagal diubah.');
            return redirect()->back()->withErrors($validate)->withInput();
    }
    public function productDelete($productId)
    {
        $deleteProduct = Product::find($productId);
        $deleteProduct->delete();

        Alert::success('Berhasil', 'Produk berhasil dihapus.');
        return redirect('produk');
    }
}
