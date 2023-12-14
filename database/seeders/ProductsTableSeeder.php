<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Product::create([
            'category_id' => 2,
            'image' => 'Fruit-TeaApple.jpg',
            'barcode' => 'PR-001',
            'title' => 'Fruit-tea Apple',
            'description' => 'Minuman teh apel',
            'buy_price' => 5000,
            'sell_price' => 6000,
            'stock' => 5000
        ]);

        Product::create([
            'category_id' => 1,
            'image' => 'Chitato.jpg',
            'barcode' => 'PR-002',
            'title' => 'Chitato BBQ',
            'description' => 'Cemilan keripik kentang rasa barbeque',
            'buy_price' => 5000,
            'sell_price' => 6000,
            'stock' => 5000
        ]);
    }
}
