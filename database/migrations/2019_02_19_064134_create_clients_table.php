<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
           $table->string('phone')->nullable();
           $table->string('phone1')->nullable();

            $table->string('image')->nullable();
            $table->string('email')->nullable();

            $table->double('first_mony', 8, 2)->nullable();
            $table->double('fin_mony', 8, 2)->nullable();
            $table->double('credit', 8, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->userstamps();
            $table->softUserstamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
