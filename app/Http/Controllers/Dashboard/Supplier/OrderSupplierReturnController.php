<?php

namespace App\Http\Controllers\Dashboard\Supplier;

use App\OrderSupplierReturn;
use App\Category;
use App\Store;
use App\Supplier;
use App\Product;
use App\MonyStock;

use DB;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderSupplierReturnController extends Controller
{
    public function create(Supplier $supplier , Request $request)
    {
        $products = Product::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id', $request->category_id);
        })->latest()->paginate(25);

        $stores = Store::all();
        $mony_stocks = MonyStock::all();

        $categories = Category::with('products')->get();
        $orders = $supplier->orders_return()->with('products')->paginate(25);
        return view('dashboard.suppliers.orders_return.create', compact( 'supplier', 'categories', 'orders','products','stores','mony_stocks'));

    }//end of create

    public function store(Request $request, Supplier $supplier)
    {

       // dd($request);
        $request->validate([
            'products' => 'required|array',
        ]);

        $this->attach_order($request, $supplier);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.ordersuppliers_return.index');

    }//end of store

    public function edit(Supplier $supplier, OrderSupplierReturn $orders_return)
    {
        $stores = Store::all();
        $mony_stocks = MonyStock::all();

        $categories = Category::with('products')->get();
        $orders = $supplier->orders_return()->with('products')->paginate(25);
        return view('dashboard.suppliers.orders_return.edit', compact('supplier', 'orders_return','orders','categories','stores','mony_stocks'));

    }//end of edit

    public function update(Request $request, Supplier $supplier, OrderSupplierReturn $orders_return)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $this->update_order($orders_return,$request);


        
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.ordersuppliers_return.index');

    }//end of update

    private function attach_order($request, $supplier)
    {
        $order = $supplier->orders_return()->create([]);
        $store = $request->store_id;
        $order->products()->attach($request->products);
        $adds1 = $request->adds1;
        $adds2 = $request->adds2;
        $disc1 = $request->disc1;
        $disc2 = $request->disc2;
        $disc3 = $request->disc3;
        $order_date = $request->order_date;
        $mony_stock = $request->mony_stock_id;

        $total_price = 0;
      //  $stck = 0;
        foreach ($request->products as $id => $quantity) {

            $product = Product::FindOrFail($id);
            $store1 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $id)->get();

            $store2 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $id);

                foreach($store1 as $disc){
                    $stck =  $disc->stock;
          
                  $store2 ->update(['stock' => $stck - $quantity['quantity']]);
         
                }     

                $total_price += $quantity['price'] * $quantity['quantity'] + $quantity['transport'] ;


        }//end of foreach
        $total_price = $total_price + $adds1 + $adds2 - $disc1 - $disc2 - $disc3;
          //  dd($total_price);
        $order->update([
            'total_price' => $total_price,
            'store_id' =>  $store,
            'disc1' => $disc1,
            'disc2' => $disc2,
            'disc3' => $disc3,
            'adds1' => $adds1,
            'adds2' => $adds2,
            'order_date' => $order_date,
            'mony_stock_id' => $mony_stock

            
        ]);
    }//end of attach order

    private function update_order( $order , $request)
    {
        $this->detach_order($order);
        $store = $request->store_id;
        $adds1 = $request->adds1;
        $adds2 = $request->adds2;
        $disc1 = $request->disc1;
        $disc2 = $request->disc2;
        $disc3 = $request->disc3;
        $order_date = $request->order_date;
        $mony_stock = $request->mony_stock_id;


        $total_price = 0;

        foreach ($request->products as $id => $quantity) {

            $product = Product::FindOrFail($id);


            $store1 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $id)->get();

            $store2 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $id);

                foreach($store1 as $disc){
                    $stck =  $disc->stock;
          
                  $store2 ->update(['stock' => $stck - $quantity['quantity']]);
         
                }
                $order->products()->sync($request->products);

                $total_price += $quantity['price'] * $quantity['quantity'] + $quantity['transport'] ;


        }//end of foreach
        $total_price = $total_price + $adds1 + $adds2 - $disc1 - $disc2 - $disc3;

        
        $order->update([
            'total_price' => $total_price,
            'store_id' =>  $store,
            'disc1' => $disc1,
            'disc2' => $disc2,
            'disc3' => $disc3,
            'adds1' => $adds1,
            'adds2' => $adds2,
            'order_date' => $order_date,
            'mony_stock_id' => $mony_stock

        ]);
    }//end of update order

    private function detach_order($order)
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
               
                  //  dd($product->pivot->quantity);

                    
                  $store2 ->update(['stock' => $stck + $product->pivot->quantity]);
         
                }
        }//end of for each


    }//end of detach order

}//end of controller
