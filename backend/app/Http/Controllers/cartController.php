<?php

namespace App\Http\Controllers;

use App\Models\cart;
use Illuminate\Http\Request;
use App\Http\Requests\cartRequest;

class cartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = cart::with([
            'variant:id,price,stock,option_1,option_2',
            'product:id,gambar,store_id',
            'product.store:id,name',
            'product.variants:id,price,stock,option_1,option_2,product_id',
        ])
            ->select(['carts.id', 'carts.user_id', 'carts.products_id', 'carts.jumlah',])
            ->where('carts.user_id', $user->id);
        if ($request->filled('oldest')) {
            $cart->orderBy('carts.updated_at', 'desc');
        } else {
            $cart->orderBy('carts.updated_at', 'asc');
        }
        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'message' => 'failed get cart data',
                'error' => 'cart not found'
            ], 404);
        }
        $res = $cart->paginate(10);
        if (!$res) {
            return response()->json([
                'status' => 'failed',
                'message' => 'failed get cart data',
                'error' => 'cart not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'success get cart data',
            'data' => $res
        ]);
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
                'message' => 'success add data cart ',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server busy',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
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
        if (!$cart) {
            return response()->json([
                'status' => 'failed',
                'message' => 'failed get cart data',
                'error' => 'cart not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'success get cart data',
            'data' => $cart
        ]);
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
                'message' => 'success update cart',
                'data' => $res,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server busy',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        $cart = cart::where('user_id', $user->id)->find($id);
        if ($cart) {
            $cart->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'success delete data cart'
            ]);
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'failed delete cart',
            'error' => 'cart not found'
        ], 404);
    }
}
