<?php

namespace Database\Seeders;

use App\Models\order;
use App\Models\order_items;
use App\Models\product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use function Illuminate\Support\now;

class orderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // order_items::query()->delete();
        // order::query()->delete();
        $products = product::with(['variant:id,product_id,price'])->get()->all();
        $user = User::get()->first();
        $order_id = Str::random(40);
        $harga = 0;
        $total_products = 0;
        $order = order::create([
            'user_id' => $user->id,
            'order_id' => $order_id,
            'order_date' => explode(" " , now())[0],
            'total_payment' => $harga,
            'total_products' => $total_products
        ]);
        foreach ($products as $product) {
            $order_items = order_items::create([
                'order_id' => $order_id,
                'product_id' => $product->id,
                'price' => $product->variant->id,
                'quantity' => $product->variant->id,
                'subtotal' => $product->variant->price * $product->variant->id,
            ]);
        }
    }
}
