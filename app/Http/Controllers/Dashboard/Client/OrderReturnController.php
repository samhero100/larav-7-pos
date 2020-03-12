<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\OrderReturn;
use App\Category;
use App\Client;
use App\Store;
use App\MonyStock;

use DB;
use App\Product;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderReturnController extends Controller
{
    public function create(Client $client, Request $request)
    {
        $products = Product::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id', $request->category_id);
        })->when($request->store_id, function ($q) use ($request) {

            return $q->where('store_id', $request->store_id);

        })->latest()->paginate(25);
        
        $categories = Category::with('products')->get();
        $stores = Store::all();
        $mony_stocks = MonyStock::all();

        $orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders_return.create', compact( 'client', 'categories', 'orders','products','stores','mony_stocks'));

    }//end of create

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'products' => 'required|array',
        ]);
       // dd($request, $client,$store);
        $this->attach_order($request, $client);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders_return.index');

    }//end of store

    public function edit(Client $client, OrderReturn $orders_return)
    {

        $stores = Store::all();
        $mony_stocks = MonyStock::all();

        $categories = Category::with('products')->get();
       // $orders_return1 = $client->orders_return()->with('products')->paginate(5);
        //dd($orders_return1);

        return view('dashboard.clients.orders_return.edit', compact('client', 'orders_return', 'categories','stores','mony_stocks'));

    }//end of edit

    public function update(Request $request, Client $client, OrderReturn $orders_return)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

       // $this->detach_order($order);

        $this->update_order($orders_return,$request);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders_return.index');

    }//end of update

    private function attach_order($request, $client)
    {
        $order = $client->orders_return()->create([]);
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
          
                  $store2 ->update(['stock' => $stck + $quantity['quantity']]);
         
                }     

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
          
                  $store2 ->update(['stock' => $stck + $quantity['quantity']]);
         
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


    private function detach_order($order_return)
    {
        $store = $order_return->store_id;

        foreach ($order_return->products as $product) {

            $product1 = Product::FindOrFail($product->id);
            $store1 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $product1->id)->get();

            $store2 = DB::table('product_store')
                ->where('store_id', '=', $store)
                ->where( 'product_id', '=', $product1->id);

                foreach($store1 as $disc){
                    $stck =  $disc->stock;
                              
                  $store2 ->update(['stock' => $stck - $product->pivot->quantity]);
         
                }

        }//end of for each


    }//end of detach order

}//end of controller
