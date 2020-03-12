<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
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

            \App\Category::create([
                'ar' => ['name' => 'مجموعه'.$product],
                'en' => ['name' => 'Category'.$product],
                ]);

        }//end of foreach

    }//end of run

}//end of seeder
