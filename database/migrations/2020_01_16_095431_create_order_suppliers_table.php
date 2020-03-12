<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->nullable();
            $table->double('disc1', 8, 2)->nullable();
            $table->double('disc2', 8, 2)->nullable();
            $table->double('disc3', 8, 2)->nullable();
            $table->double('adds1', 8, 2)->nullable();
            $table->double('adds2', 8, 2)->nullable();
            $table->double('order_date', 8, 2)->nullable();
            $table->double('paid', 8, 2)->nullable();
            $table->integer('mony_stock_id')->nullable();

            $table->integer('supplier_id')->unsigned();
            $table->double('total_price', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->userstamps();
            $table->softUserstamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_suppliers');
    }
}
