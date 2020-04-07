<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Eloquent::unguard();

		//disable foreign key check for this connection before running seeders
       Schema::disableForeignKeyConstraints();
        // DB::table('roles')->truncate();

        // DB::table('permissions')->truncate();
        //         DB::table('users')->truncate();

      //  DB::disableForeignKeyCheck();

       $this->call(LaratrustSeeder::class);
        $this->call(UsersTableSeeder::class);
     // $this->call(StoresTableSeeder::class);

    //    $this->call(CategoriesTableSeeder::class);
       $this->call(ProductsTableSeeder::class);
    //    $this->call(ClientsTableSeeder::class);



        //DB::enableForeignKeyCheck();
        //Schema::enableForeignKeyConstraints();

        
    }
}
