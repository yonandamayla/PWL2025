<?php

namespace App\Http\Controllers;

use App\Models\OrderItemModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItemModel::with(['order', 'item'])->get();
        return response()->json($orderItems);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $orderItem = OrderItemModel::create($validated);
        return response()->json($orderItem, 201);
    }

    public function show($id)
    {
        $orderItem = OrderItemModel::with(['order', 'item'])->find($id);
        if (!$orderItem) {
            throw new NotFoundHttpException("OrderItem with ID {$id} not found");
        }
        return response()->json($orderItem);
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItemModel::find($id);
        if (!$orderItem) {
            throw new NotFoundHttpException("OrderItem with ID {$id} not found");
        }

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $orderItem->update($validated);
        return response()->json($orderItem);
    }

    public function destroy($id)
    {
        $orderItem = OrderItemModel::find($id);
        if (!$orderItem) {
            throw new NotFoundHttpException("OrderItem with ID {$id} not found");
        }

        $orderItem->delete();
        return response()->json(['message' => 'OrderItem deleted successfully']);
    }
}