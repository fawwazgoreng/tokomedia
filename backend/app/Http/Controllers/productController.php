<?php

namespace App\Http\Controllers;

use App\Http\Requests\productRequest;
use App\Models\categories;
use App\Models\product;
use App\Models\variants;
use App\services\pathphoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(protected pathphoto $pathPhoto) {}

    public function index(Request $request)
    {
        try {
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
    public function store(productRequest $request)
    {
        try {
            $store = $request->user();
            $data = $request->validated();
            $path = '';
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $path = $this->pathPhoto->createPathPhoto($file, 'products');
            }
            $product = product::create([
                'name' => $data['name'],
                'gambar' => $path,
                'store_id' => $store->id
            ]);
            foreach ($data['variants'] as $variant) {
                variants::create([
                    'product_id' => $product->id,
                    'sku' => 'TM-' . $product->id . '-' . Str::random(10),
                    'stock' => $variant['stock'] ?? 0,
                    'price' => $variant['price'] ?? 0,
                    'option_1' => $variant['option_1'],
                    'option_2' => $variant['option_2'] ?? ''
                ]);
            }
            foreach ($data['categories'] as $category) {
                $categories = categories::select(['id', 'name'])->where('name',  strtolower($category))->first();
                $product->categories()->attach($categories->id);
            };
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menmbah product',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ] , 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = product::with([
                'variants' => function ($query) {
                    $query->select(['variants.id', 'product_id', 'sku', 'stock', 'price', 'option_1', 'option_2', 'updated_at']);
                },
                'store' => function ($query) {
                    $query->select(['stores.id', 'stores.name', 'foto_profil']);
                },
                'categories' => function ($query) {
                    $query->select(['categories.id', 'categories.name']);
                }
            ])->select(['products.id', 'products.name', 'products.gambar', 'products.store_id'])->find($id);
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil mencari data product',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ] , 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(productRequest $request, string $id)
    {
        try {
            $store = $request->user();
            $data = $request->validated();
            $product = product::with([
                'variants' => function ($query) {
                    $query->select([
                        'variants.id',
                        'product_id'
                    ]);
                },
                'store' => function ($query) {
                    $query->select([
                        'stores.id',
                    ]);
                },
                'categories' => function ($query) {
                    $query->select([
                        'categories.id',
                    ]);
                }
            ])->select(['products.id', 'products.name', 'products.gambar', 'products.store_id'])->where('store_id', $store->id)->find($id);
            $path = $product->gambar;
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $path = $this->pathPhoto->updatePathPhoto($path, $file, 'products');
            }
            if ($data['variants']) foreach ($data['variants'] as $variant) {
                variants::updateOrCreate(
                    ['id' => $variant['id']],
                    [
                        'product_id' => $product->id,
                        'sku' => 'TM-' . $product->id . '-' . Str::random(10),
                        'stock' => $variant['stock'],
                        'price' => $variant['price'],
                        'option_1' => $variant['option_1'],
                        'option_2' => $variant['option_2'] ?? ""
                    ]
                );
            }
            if ($data['categories']) {
                foreach ($data['categories'] as $category) {
                    $categories = categories::select(['id', 'name'])->where('name', $category)->first();
                    if ($categories) {
                        $product->categories()->attach($categories->id);
                    }
                }
            };
            if ($data['delete_variants']) foreach ($data['delete_variants'] as $id) {
                $variant = variants::select(['id', 'product_id'])->where('product_id', $product->id)->find($id);
                if ($variant) {
                    $variant->delete();
                }
            }
            if ($data['delete_categories']) foreach ($data['delete_categories'] as $id) {
                $category = categories::find($id);
                if ($product->whereAttachTo('categories', $category)) {
                    $product->categories()->detach($id);
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil update product',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'server sedang error',
            ] , 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $store = $request->user();
        $product = product::find($id)->where('soter_id', $store->id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil delete product',
                'data' => $product
            ]);
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'data product tidak ditemukan',
        ], 404);
    }
}
