<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MonyStocksTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mony_stock_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mony_stock_id')->unsigned();
            $table->string('name');

            $table->string('locale')->index();

            $table->unique(['mony_stock_id', 'locale']);
            $table->foreign('mony_stock_id')->references('id')->on('mony_stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mony_stock_translations');
    }
}
