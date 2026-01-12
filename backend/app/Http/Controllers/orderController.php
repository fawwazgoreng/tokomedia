<?php

namespace App\Http\Controllers;

use App\Http\Requests\orderRequest;
use App\Models\order;
use App\Models\order_items;
use App\Models\User;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class orderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $order = order::with([
            'order_items:id,order_id,price,quantity,variants,subtotal'
        ])->select(['id', 'user_id', 'order_id', 'order_date', 'payment_status', 'status', 'total_payment', 'total_products'])->where('user_id', $user->id);
        if ($request->filled('oldest')) {
            $order->orderBy('order_date', 'desc');
        } else {
            $order->orderBy('order_date', 'asc');
        }
        $res = $order->paginate(10);
        if (!$res) {
            return response()->json([
                'status' => 'failed',
                'message' => 'failed get order data',
                'error' => 'order not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'success get order data',
            'data' => $res
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(orderRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $order = order::create([
                'user_id' => $user->id,
                'order_date' => $data['order_date'] ?? now(),
                'total_payment' => 0,
                'total_products' => 0,
            ]);
            foreach ($data['order_items'] as $items) {
                $subtotal = $items['price'] * $items['quantity'];
                $order_items = order_items::create([
                    'order_id' => $order->order_id,
                    'product_id' => $items['product_id'],
                    'price' => $items['price'],
                    'quantity' => $items['quantity'],
                    'variants' => $items['variants'],
                    'subtotal' => $subtotal,
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'success add order'
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
    public function show(string $id, Request $request)
    {
        $user = $request->user();
        $order = order::with([
            'order_items:id,order_id,price,quantity,variants,subtotal'
        ])->select(['id', 'user_id', 'order_id', 'order_date', 'payment_status', 'status', 'total_payment', 'total_products'])->where('user_id', $user->id)->find($id);
        if (!$order) {
            return response()->json([
                'status' => 'failed',
                'message' => 'failed get order data',
                'error' => 'order not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'success get order data',
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(orderRequest $request, string $id)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $order = order::select(['id', 'user_id', 'order_id', 'total_products', 'total_payment'])->where('user_id', $user->id)->find($id);
            if (!$order) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'failed get order data',
                    'error' => 'order not found'
                ], 404);
            }
            if ($data['order_items']) {
                foreach ($data['order_items'] as $items) {
                    $subtotal = $items['price'] * $items['quantity'];
                    $order = order_items::updateOrCreate([
                        'id' => $items['id'],
                        'product_id' => $items['product_id'],
                        'order_id' => $order->order_id
                    ], [
                        'order_id' => $order->order_id,
                        'product_id' => $items['product_id'],
                        'price' => $items['price'],
                        'quantity' => $items['quantity'],
                        'variants' => $items['variants'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }
            if ($data['delete_items']) {
                foreach ($data['delete_items'] as $items) {
                    $order_items = order_items::where('order_id', $order->order_id)->find($items);
                    if ($order_items) {
                        $order_items->delete();
                    };
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'success update data order'
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
        $order = order::where('user_id', $user->id)->find($id)->first();
        if ($order) {
            $order->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'success delete order',
            ]);
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'failed delete order data',
            'error' => 'order not found'
        ], 404);
    }
}
