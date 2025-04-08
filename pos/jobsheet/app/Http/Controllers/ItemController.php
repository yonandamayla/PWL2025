<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemController extends Controller
{
    public function index()
    {
        $items = ItemModel::with('itemType')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_type_id' => 'required|exists:item_types,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $item = ItemModel::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = ItemModel::with('itemType')->find($id);
        if (!$item) {
            throw new NotFoundHttpException("Item with ID {$id} not found");
        }
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = ItemModel::find($id);
        if (!$item) {
            throw new NotFoundHttpException("Item with ID {$id} not found");
        }

        $validated = $request->validate([
            'item_type_id' => 'required|exists:item_types,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ItemModel::find($id);
        if (!$item) {
            throw new NotFoundHttpException("Item with ID {$id} not found");
        }

        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}