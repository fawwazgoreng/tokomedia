<?php

namespace Database\Seeders;

use App\Models\categories;
use App\Models\product;
use App\Models\variants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class categorisSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = categories::create([
            'name' => 'categori 2'
        ]);
        for ($i = 1; $i < 10; $i++) {
            $product = product::find($i);
            $product->categories()->attach($categories->id);
            variants::create([
                'product_id' => $product->id,
                'sku' => Str::random(10),
                'stock' => 10000,
                'price' => 12000000,
                'option_1' => 'xl',
                'option_2' => 'xxl',
            ]);
        }
    }
}
