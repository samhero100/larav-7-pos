<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 0; $i < 100; $i++) {
            App\Category::create([
                'ar' => ['name' => 'مجموعه'.$faker->name],
                'en' => ['name' => 'Category'.$faker->name],
            ]);
        }

        for($i = 0; $i < 100; $i++) {
            App\Store::create([
                'ar' => ['name' => 'مجموعه'.$faker->name],
                'en' => ['name' => 'Store'.$faker->name],
            ]);
        }
        for($i = 0; $i < 1000; $i++) {
            App\Client::create([
                'ar' => ['name' => 'عميل'.$faker->name],
                'en' => ['name' => 'Client'.$faker->name],
            ]);
        }

        for($i = 0; $i < 1000; $i++) {
            App\Product::create([
                 'category_id' => $faker->numberBetween($min = 1, $max = 100),
                'ar' => ['name' => 'صنف'.$faker->name, 'description' => 'وصف'.$faker->name],
                'en' => ['name' => 'Product'.$faker->name, 'description' => 'Description'.$faker->name],
                'purchase_price' => $faker->numberBetween($min = 1, $max = 1000),
                'sale_price' => $faker->numberBetween($min = 1, $max = 1000),
            ]);
        }

        for($i = 0; $i < 1000; $i++) {
            App\ProductStore::create([
                 'product_id' => $faker->numberBetween($min = 1, $max = 100000),
                 'store_id' => $faker->numberBetween($min = 1, $max = 100),
            ]);
        }

        // $products = ['1', '2'];

        // foreach ($products as $product) {

        //     \App\Product::create([
        //         'category_id' => 1,
        //         'ar' => ['name' => 'صنف'.$product, 'description' => 'وصف'.$product],
        //         'en' => ['name' => 'Product'.$product, 'description' => 'Description'.$product],
        //         'purchase_price' => 100,
        //         'sale_price' => 150,
        //         'stock' => 100,
        //     ]);

        // }//end of foreach

    }//end of run

}//end of seeder
