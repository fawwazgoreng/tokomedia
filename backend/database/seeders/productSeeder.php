<?php

namespace Database\Seeders;

use App\Models\categories;
use App\Models\product;
use App\Models\store;
use App\Models\variants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        categories::query()->delete();
        store::query()->delete();
        product::query()->delete();
        $categories = categories::create([
            'name' => 'categories 1'
        ]);
        $store = store::create([
            'name' => 'store test',
            'email' => 'test@gmail.com',
            'password' => Hash::make("test1234")
        ]);
        for ($i = 0 ; $i < 10 ; $i++) {
            $product = product::create([
                'name' => 'product ' . $i,
                'store_id' => $store->id,
                'gambar' => 'gambar 123' . $i
            ]);
            variants::create([
                'product_id' => $product->id,
                'sku' => Str::random(10),
                'stock' => 12,
                'price' => 12,
                'option_1' => 'ini option_1',
                'option_2' => 'ini option_2',
            ]);
            Log::info($product);
            $product->categories()->attach($categories->id);
        }
    }
}
