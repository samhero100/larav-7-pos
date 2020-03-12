<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

            Route::get('/', 'WelcomeController@index')->name('welcome');
           

            //category routes
            Route::resource('categories', 'CategoryController')->except(['show']);

        
    


            //product routes
            Route::resource('products', 'ProductController')->except(['show']);
            Route::get('edit_stores/{id}', 'ProductController@edit_stores')->name('product.edit_stores');
            Route::put('update_stores/{id}', 'ProductController@update_stores')->name('product.update_stores');

            //stores routes
            Route::resource('stores', 'StoreController')->except(['show']);

            //client routes
            Route::resource('clients', 'ClientController')->except(['show']);
            Route::resource('clients.orders', 'Client\OrderController')->except(['show']);
            Route::resource('clients.orders_return', 'Client\OrderReturnController')->except(['show']);

            //order  routes
            Route::resource('orders', 'OrderController');
            Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');
            Route::resource('orders_return', 'OrderReturnController');
            Route::get('/orders_return/{order}/products', 'OrderReturnController@products')->name('orders_return.products');

            // search products for order route
            Route::get('prods', 'OrderController@prods')->name('orders.prods');
            Route::get('get_prods', 'OrderController@get_prods')->name('orders.get_prods');
            Route::get('get_pro', 'OrderController@get_pro')->name('orders.get_pro');
            Route::get('get_pro1',  'OrderSuppliersController@get_pro1')->name('orders.get_pro1');

                                   // suppliers routes
           Route::resource('suppliers', 'SupplierController')->except(['show']);
           Route::resource('suppliers.orders', 'Supplier\OrdersSuppliersController')->except(['show']);
           Route::resource('suppliers.orders_return', 'Supplier\OrderSupplierReturnController')->except(['show']);

            //order supplier routes
            Route::resource('orders_suppliers', 'OrderSuppliersController');
            Route::get('/orders_suppliers/{order}/products', 'OrderSuppliersController@products')->name('orders_suppliers.products');
            Route::resource('ordersuppliers_return', 'OrderSupplierReturnController');
            Route::get('/ordersuppliers_return/{order}/products', 'OrderSupplierReturnController@products')->name('ordersuppliers_return.products');

            //user routes
            Route::resource('users', 'UserController')->except(['show']);
            Route::resource('roles', 'RoleController')->except(['show']);

        });//end of dashboard routes
    });
    Route::get('get_prods', 'OrderController@get_prods')->name('orders.get_prods');
    Route::get('get_prods1', 'OrderController@get_prods1')->name('orders.get_prods1');




