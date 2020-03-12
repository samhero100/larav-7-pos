<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = ['1', '2'];

        foreach ($products as $product) {

            \App\Client::create([
                'ar' => ['name' => 'عميل'.$product, 'address' => 'عنوان'.$product],
                'en' => ['name' => 'customer'.$product, 'address' => 'address'.$product],
               
               // 'phone' => 123456,

                ]);

        }//end of foreach

    }//end of run

}//end of seeder
