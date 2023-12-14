<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Category::create([
            'name' => 'Snacks',
            'image' => 'Snacks.webp',
            'description' => 'Snacks',
        ]);

        Category::create([
            'name' => 'Drinks',
            'image' => 'Drinks.webp',
            'description' => 'Drinks',
        ]);
    }
}
