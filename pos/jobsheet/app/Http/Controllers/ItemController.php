<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\ItemTypeModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        // Get all item types for filtering
        $itemTypes = ItemTypeModel::all();

        // Get all items with their related item types
        $items = ItemModel::with('itemType')->get();

        // Set the active menu for sidebar highlighting
        $activeMenu = 'items';

        return view('items.index', compact('itemTypes', 'items', 'activeMenu'));
    }

    /**
     * Get item details for AJAX request.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function getItemDetails($id)
    {
        $item = ItemModel::with('itemType')->findOrFail($id);
        return response()->json($item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        // Get all item types for the dropdown
        $itemTypes = ItemTypeModel::all();

        // Set the active menu for sidebar highlighting
        $activeMenu = 'items';

        return view('items.create', compact('itemTypes', 'activeMenu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'item_type_id' => 'required|exists:item_types,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare item data
        $itemData = $request->only([
            'name',
            'item_type_id',
            'price',
            'stock',
            'description'
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $itemData['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        // Create new item record
        $item = ItemModel::create($itemData);

        // Flash success message to session
        return redirect('/items')->with('success', 'Barang berhasil ditambahkan');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $item = ItemModel::findOrFail($id);
        $itemTypes = ItemTypeModel::all();

        // Set the active menu for sidebar highlighting
        $activeMenu = 'items';

        return view('items.edit', compact('item', 'itemTypes', 'activeMenu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'item_type_id' => 'required|exists:item_types,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the item to update
        $item = ItemModel::findOrFail($id);

        // Prepare item data
        $itemData = $request->only([
            'name',
            'item_type_id',
            'price',
            'stock',
            'description'
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            $this->deleteExistingPhoto($item);

            // Upload new photo
            $itemData['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        // Update the item
        $item->update($itemData);

        // Flash success message to session
        return redirect('/items')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $item = ItemModel::findOrFail($id);

        // Delete item's photo if exists
        $this->deleteExistingPhoto($item);

        // Delete the item
        $item->delete();

        // Flash success message to session
        return redirect('/items')->with('success', 'Barang berhasil dihapus');
    }

    /**
     * Handle photo upload and return the file path.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return string
     */
    private function handlePhotoUpload($photo)
    {
        $filename = 'item_' . time() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('public/items', $filename);
        return 'storage/items/' . $filename;
    }

    /**
     * Delete existing photo if it exists.
     *
     * @param  ItemModel  $item
     * @return void
     */
    private function deleteExistingPhoto(ItemModel $item)
    {
        if ($item->photo && Storage::exists(str_replace('storage/', 'public/', $item->photo))) {
            Storage::delete(str_replace('storage/', 'public/', $item->photo));
        }
    }
}
