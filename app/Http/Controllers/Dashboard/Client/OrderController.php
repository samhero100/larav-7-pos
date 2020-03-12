<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Category;
use App\Client;
use App\Order;
use App\Store;
use App\MonyStock;

use DB;
use App\Product;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create(Client $client, Request $request)
    {
    //     $ff = app()->getLocale();
    //   $nn = strval($ff);
    //     $dd = trim($ff, '');
    //           dd($dd);

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
        return view('dashboard.clients.orders.create', compact( 'client', 'categories', 'orders','products','stores','mony_stocks'));

    }//end of create

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'products' => 'required|array',
        ]);
       // dd($request, $client,$store);
        $this->attach_order($request, $client);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');

    }//end of store

    public function edit(Client $client, Order $order)
    {
        $stores = Store::all();
        $mony_stocks = MonyStock::all();

        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.edit', compact('client', 'order', 'categories', 'orders','stores','mony_stocks'));

    }//end of edit

    public function update(Request $request, Client $client, Order $order)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

       // $this->detach_order($order);

        $this->update_order($order,$request);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');

    }//end of update

    private function attach_order($request, $client)
    {
        $order = $client->orders()->create([]);
        $store = $request->store_id;

        $adds1 = $request->adds1;
        $adds2 = $request->adds2;
        $disc1 = $request->disc1;
        $disc2 = $request->disc2;
        $disc3 = $request->disc3;
        $order_date = $request->order_date;
        $mony_stock = $request->mony_stock_id;

        $total_price = 0;
        $prods = $request->products;
       // dd($prods);
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
             //   $prods->price[$id] =  $quantity['price'] * $quantity['quantity'] + $quantity['transport'];

        }//end of foreach
        $total_price = $total_price + $adds1 + $adds2 - $disc1 - $disc2 - $disc3;
        $order->products()->attach($request->products);

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
               
                   /// dd($product->pivot->quantity);

                    
                  $store2 ->update(['stock' => $stck + $product->pivot->quantity]);
         
                }

        }//end of for each


    }//end of detach order

}//end of controller
