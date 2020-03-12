<?php

use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = ['1', '2'];

        // foreach ($products as $product) {

            \App\Store::create([

                'ar' => ['name' => 'المحزنالرئيسي'],
                'en' => ['name' => 'Main Store'],

                ]);

        // }//end of foreach

    }//end of run

}//end of seeder
