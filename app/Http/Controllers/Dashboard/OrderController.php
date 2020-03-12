<?php

namespace App\Http\Controllers\Dashboard;

use App\Order;
use App\Product;
use DB;
use App\Category;
use DataTables;
use Validator;
use Lang;
use Illuminate\Support\Str;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::whereHas('client', function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->paginate(10);

        
        return view('dashboard.orders.index', compact('orders'));

    }//end of index

    public function products(Order $order)
    {
        
        $products = $order->products;
        return view('dashboard.orders._products', compact('order', 'products'));

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
    // public function setLocale($locale)
    // {
    //     if (array_key_exists($locale, Config::get('languages'))) 
    //     {
    //         Session::put('app_locale', $locale);
    //     }
    //     return redirect()->back();
    // }
    public function get_prods(Request $request)
    {

        if($request->ajax())
        {
  
            $categories = Category::all();
            $data = Product::with('category:id','stores')->get();


          
            return DataTables::of($data)

            ->addColumn('category',function($data){
                return $data->category->translate(app()->getLocale())->name;
            })

            ->addColumn('stock',function($data){
                return $data->total_stock();
            })

            ->addColumn('product',function($data){
                return $data->translate(app()->getLocale())->name;
            })
            ->addColumn('action', function($data){
                       
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

                ->rawColumns(['action'])

                ->make(true);

        }

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

    public function destroy(Order $order)
    {
        $store = $order->store_id;

        foreach ($order->products as $product) {

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

                    
                  $store2 ->update(['stock' => $stck + $product->pivot->quantity]);
         
                }

        }//end of for each

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    
    }//end of order

}//end of controller
