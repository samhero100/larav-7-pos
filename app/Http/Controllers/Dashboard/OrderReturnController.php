<?php

namespace App\Http\Controllers\Dashboard;

use App\OrderReturn;
use App\Product;
use DB;
use App\Category;
use DataTables;
use Validator;
use Lang;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OrderReturnController extends Controller
{
    public function index(Request $request)
    {
        $orders_return = OrderReturn::whereHas('client', function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->paginate(10);
       // dd($orders_return);

        return view('dashboard.orders_return.index', compact('orders_return'));

    }//end of index

    public function products(OrderReturn $order)
    {
        $products = $order->products;
       // dd($products);
        return view('dashboard.orders_return._products', compact('order', 'products'));

    }//end of products
    public function prods(Request $request)
    {
        $name = $request->input('search', '');
        $categories = Category::all();

        $products = Product::whereTranslationLike('name', '%' . $name . '%')

       ->latest();

        return (compact('categories', 'products'));

    }//end of products

    public function get_pro(Request $request)
    {
        return view('dashboard.sample_data');


    }
    public function get_prods(Request $request)
    {

        if($request->ajax())
        {
            $categories = Category::all();
           // $data = Product::where('id', $request->id)->with('category:id')->with('stores')->get();

            $data = Product::with('category:id')->with('stores')->get();
            return DataTables::of($data)
            ->addColumn('category',function($data){
                return $data->category->translate(app()->getLocale())->name;
            })
            ->addColumn('product',function($data){
                return $data->translate(app()->getLocale())->name;
            })
            ->addColumn('action', function($data){
                        // $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        // $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                       
                        $button = '<button type="button"
                        data-name="'.$data->name.'";
                        id="'.'product-'.$data->id.'"

                        data-id="'.$data->id.'"
                        data-price="'.$data->sale_price.'"

                        class="btn btn-success btn-sm add-product-btn">
                        <i class="fa fa-plus"></i>
                        </button>';

                         return $button;
                        })

                        // ->addColumn('profit_percent',function($product){
                        //     return $product->profit_percent;
                        // })
                ->rawColumns(['action','category'])
                ->make(true);
        }

      //   return view('sample_data');
    }

    public function get_prods1(Request $request)
    {
        if($request->ajax())
        {
            $categories = Category::all();

            $data = Product::with('category:id')->get();
            return DataTables::of($data)
            ->addColumn('category',function($data){
                return $data->category->translate(app()->getLocale())->name;
            })
            ->addColumn('product',function($data){
                return $data->translate(app()->getLocale())->name;
            })
                    ->addColumn('action', function($data){
                        // $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        // $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                       
                        $button = '<button type="button"
                        data-name="'.$data->name.'";
                        id="'.'product-'.$data->id.'"

                        data-id="'.$data->id.'"
                        data-price="'.$data->purchase_price.'"

                        class="btn btn-success btn-sm add-product-btn">
                        <i class="fa fa-plus"></i>
                        </button>';

                         return $button;
                        })

                        // ->addColumn('profit_percent',function($product){
                        //     return $product->profit_percent;
                        // })
                    ->rawColumns(['action','category'])
                    ->make(true);
        }

      //   return view('sample_data');
    }

    public function destroy(OrderReturn $orders_return)
    {
       // dd($orders_return->id);
        $store = $orders_return->store_id;

        foreach ($orders_return->products as $product) {

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

        $orders_return->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders_return.index');
    
    }//end of order

}//end of controller
