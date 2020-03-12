<?php

namespace App\Http\Controllers\Dashboard;

use App\OrderSupplierReturn;
use App\Product;
use DB;
use App\Category;
use DataTables;
use Validator;
use Lang;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OrderSupplierReturnController extends Controller
{
    public function index(Request $request)
    {
        $orders_suppliers = OrderSupplierReturn::whereHas('supplier', function ($q) use ($request) {
            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->paginate(25);
       // dd($orders_supplier);

        return view('dashboard.ordersuppliers_return.index', compact('orders_suppliers'));

    }//end of index

    public function products(OrderSupplierReturn $order)
    {
        $products = $order->products;
        return view('dashboard.ordersuppliers_return._products', compact('order', 'products'));

    }//end of products
    public function get_pro1(Request $request)
    {
        return view('dashboard.sample_data_imp');


    }


    public function get_prods(Request $request)
    {
        if($request->ajax())
        {
            $categories = Category::all();

            $data = Product::all();
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        // $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        // $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                       
                        $button = '<button type="button"
                        data-name="'.$data->name.'";
                        id="'.'product-'.$data->id.'"
                        data-name="'.$data->name.'"
                        data-id="'.$data->id.'"
                        data-price="'.$data->purchase_price.'"

                        class="btn btn-success btn-sm add-product-btn">
                        <i class="fa fa-plus"></i>
                        </button>';

                         return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      //   return view('sample_data');
    }


    public function destroy(OrderSupplierReturn $orders_supplier)
    {
       // dd($orders_supplier);
        $store = $orders_supplier->store_id;

        foreach ($orders_supplier->products as $product) {

            $product1 = Product::FindOrFail($product->id);
            $store1 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $product1->id)->get();

            $store2 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $product1->id);

                foreach($store1 as $disc){
                    $stck =  $disc->stock;
               
                   /// dd($product->pivot->quantity);

                    
                  $store2 ->update(['stock' => $stck - $product->pivot->quantity]);
         
                }

        }//end of for each

        $orders_supplier->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.ordersuppliers_return.index');
    
    }//end of order

}//end of controller
