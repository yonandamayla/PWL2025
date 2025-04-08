<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    public function index()
    {
        $orders = OrderModel::with('user')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:pending,completed,cancelled',
            'order_date' => 'required|date',
        ]);

        $order = OrderModel::create($validated);
        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = OrderModel::with('user')->find($id);
        if (!$order) {
            throw new NotFoundHttpException("Order with ID {$id} not found");
        }
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = OrderModel::find($id);
        if (!$order) {
            throw new NotFoundHttpException("Order with ID {$id} not found");
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:pending,completed,cancelled',
            'order_date' => 'required|date',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = OrderModel::find($id);
        if (!$order) {
            throw new NotFoundHttpException("Order with ID {$id} not found");
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}