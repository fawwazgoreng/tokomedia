<?php

namespace Database\Seeders;

use App\Models\cart;
use App\Models\product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class cartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = product::with('variant')->paginate(10);
        // $user = User::create([
        //     'name' => 'test123',
        //     'email' => 'test@gmail.com',
        //     'password' => Hash::make('test123'),
        // ]);
        $user = User::get()->first();
        foreach ($products as $product) {
            $cart = cart::create([
                'user_id' => $user->id,
                'products_id' => $product->id,
                'variants_id' => $product->variant->id,
                'jumlah' => 10
            ]);
        }
    }
}
