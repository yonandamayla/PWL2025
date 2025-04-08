<?php

namespace App\Http\Controllers;

use App\Models\ItemTypeModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemTypeController extends Controller
{
    public function index()
    {
        $itemTypes = ItemTypeModel::all();
        return response()->json($itemTypes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $itemType = ItemTypeModel::create($validated);
        return response()->json($itemType, 201);
    }

    public function show($id)
    {
        $itemType = ItemTypeModel::find($id);
        if (!$itemType) {
            throw new NotFoundHttpException("ItemType with ID {$id} not found");
        }
        return response()->json($itemType);
    }

    public function update(Request $request, $id)
    {
        $itemType = ItemTypeModel::find($id);
        if (!$itemType) {
            throw new NotFoundHttpException("ItemType with ID {$id} not found");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $itemType->update($validated);
        return response()->json($itemType);
    }

    public function destroy($id)
    {
        $itemType = ItemTypeModel::find($id);
        if (!$itemType) {
            throw new NotFoundHttpException("ItemType with ID {$id} not found");
        }

        $itemType->delete();
        return response()->json(['message' => 'ItemType deleted successfully']);
    }
}