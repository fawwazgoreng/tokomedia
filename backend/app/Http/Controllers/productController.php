<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\store;
use App\Models\User;
use Illuminate\Http\Request;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $product = product::with(['categories'  => function ($query) {
            $query->select(['categories.id', 'categories.name']);
        }, 'variant' => function ($query) {
            $query->select(['variants.id', 'variants.product_id', 'variants.option_1']);
        }])->select(['products.id', 'products.name', 'products.gambar', 'products.updated_at']);
        if ($request->name) {
            $name = $request->name;
            $product->where('name', 'LIKE', $name . "%");
        }
        if ($request->filled('categories')) {
            $product->whereExists(function ($q) use ($request) {
                $count = count($request->categories);
                $q->selectRaw(1)
                    ->from('categories_products as cp')
                    ->join('categories as c', 'c.id', '=', 'cp.categories_id')
                    ->whereColumn('cp.product_id', 'products.id')
                    ->whereIn('c.name', $request->categories)
                    ->groupBy('cp.product_id')
                    ->havingRaw('COUNT(DISTINCT c.id) = ?', [$count]);
            });
        }
        if ($request->exists('oldest')) {
            $product->orderBy('products.updated_at', 'desc');
        } else {
            $product->orderBy('products.updated_at', 'asc');
        }
        $res = $product->paginate(10);
        return response()->json([
            'data' => $res
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $store = $request->user();
        $data = $request->validated();
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
