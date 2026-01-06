<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\cartRequest;
use App\Http\Resources\cartsResource;
use App\Models\variants;

class cartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
        $user = $request->user();
        $cart = cart::join('products as p', 'carts.products_id', '=', 'p.id')
            ->join('stores as s', 'p.store_id', '=', 's.id')
            ->join('categories_products as cp', 'p.id', '=', 'cp.product_id')
            ->join('categories as c', 'cp.categories_id', '=', 'c.id')
            ->join('variants as v', 'carts.variants_id', '=', 'v.id')
            ->select([
                'carts.id',
                'carts.user_id',
                'carts.products_id',
                'carts.jumlah',
                'p.name as product_name',
                's.name as store_name',
                'p.gambar',
                'c.name as categories_name',
                'c.id as categories_id',
                'v.id as variant_id',
                'v.option_1',
                'v.option_2',
                'v.price as variant_price',
                'v.stock as variant_stock',
            ])
            ->where('carts.user_id', $user->id);
        if ($request->filled('oldest')) {
            $cart->orderBy('carts.updated_at', 'desc');
        } else {
            $cart->orderBy('carts.updated_at', 'asc');
        }
        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'message' => 'data cart tidak ditemukan',
            ], 404);
        }
        $res = $cart->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil mendapatkan data cart',
            'data' => $res
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ] , 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(cartRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $cart = cart::create([
                'user_id' => $user->id,
                'products_id' => $data['products_id'],
                'jumlah' => $data['jumlah'] ?? 0,
                'variants_id' => $data['variants_id'],
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menambah data cart ',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $user = $request->user();
            $cart = cart::join('products as p', 'carts.products_id', '=', 'p.id')
                ->join('stores as s', 'p.store_id', '=', 's.id')
                ->join('variants as v', 'carts.variants_id', '=', 'v.id')
                ->select([
                    'carts.id',
                    'carts.user_id',
                    'carts.products_id',
                    'carts.jumlah',
                    'v.option_1',
                    'v.option_2',
                    'p.name as product_name',
                    's.name as store_name',
                    'p.gambar',
                ])
                ->where('carts.user_id', $user->id)->find($id);
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil mendapatkan data cart',
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(cartRequest $request, string $id)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $cart = cart::select(['id', 'jumlah', 'variants_id'])->where('user_id', $user->id)->findOrFail($id);
            $res = $cart->update([
                'jumlah' => $data['jumlah'] ?? $cart->jumlah,
                'variants_id' => $data['variants_id'] ?? $cart->variants_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil update cart',
                'data' => $res,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // $user = $request->user();
        $user = User::get()->first();
        $cart = cart::where('user_id', $user->id)->find($id);
        if ($cart) {
            $cart->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menghapus data cart'
            ]);
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'data cart tidak ditemuka'
        ], 404);
    }
}
