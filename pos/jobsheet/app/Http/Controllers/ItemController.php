<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\ItemTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Check if user is admin
     */
    public function isAdmin($user = null)
    {
        $user = $user ?? auth()->user();
        return $user && $user->role_id === 1;  // Assuming role_id 1 is admin
    }

    /**
     * Display items management page
     */
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list' => [
                'Home' => route('home'),
                'Daftar Barang' => null
            ]
        ];

        $itemTypes = ItemTypeModel::all();

        return view('items.index', compact('breadcrumb', 'itemTypes'))
            ->with('activeMenu', 'items');
    }

    /**
     * Get all items for AJAX DataTables request
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Changed 'image' to 'photo' in the select statement
            $items = ItemModel::with('itemType')
                ->select('id', 'name', 'description', 'price', 'stock', 'photo', 'item_type_id')
                ->orderBy('id', 'desc'); // Add this line to sort by newest items first

            // Filter by item type if provided
            if ($request->has('item_type_id') && !empty($request->item_type_id)) {
                $items->where('item_type_id', $request->item_type_id);
            }

            // Filter by stock level if provided
            if ($request->has('stock_filter')) {
                switch ($request->stock_filter) {
                    case 'low':
                        $items->where('stock', '<', 10);
                        break;
                    case 'out':
                        $items->where('stock', '=', 0);
                        break;
                    case 'available':
                        $items->where('stock', '>', 0);
                        break;
                }
            }

            return DataTables::of($items)
                ->addIndexColumn()
                ->addColumn('image_url', function ($row) {
                    // Also update this to use 'photo' instead of 'image'
                    return !empty($row->photo)
                        ? asset($row->photo) // Using asset() directly since path includes 'storage/'
                        : asset('images/no-image.png');
                })
                ->addColumn('type_name', function ($row) {
                    return $row->itemType ? $row->itemType->name : 'N/A';
                })
                ->addColumn('formatted_price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->addColumn('stock_status', function ($row) {
                    if ($row->stock <= 0) {
                        return '<span class="badge badge-danger">Habis</span>';
                    } elseif ($row->stock < 10) {
                        return '<span class="badge badge-warning">Rendah</span>';
                    } else {
                        return '<span class="badge badge-success">Tersedia</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                        <button type="button" data-id="' . $row->id . '" class="btn btn-info btn-sm view-btn" title="Lihat detail">
                            <i class="fas fa-eye"></i>
                        </button>';

                    // Only add edit/delete buttons for admin users        
                    if ($this->isAdmin()) {
                        $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-primary btn-sm edit-btn" title="Edit barang">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn" title="Hapus barang">
                            <i class="fas fa-trash"></i>
                        </button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'stock_status'])
                ->make(true);
        }

        return abort(404);
    }

    /**
     * Store a new item
     */
    public function store(Request $request)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'item_type_id' => 'required|exists:item_types,id',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $item = new ItemModel();
            $item->name = $request->name;
            $item->description = $request->description;
            $item->price = $request->price;
            $item->stock = $request->stock;
            $item->item_type_id = $request->item_type_id;

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();

                // Store the file
                $path = $image->storeAs('public/items', $imageName);
                $item->photo = 'storage/items/' . $imageName; // Changed from 'image' to 'photo'
            }

            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating item: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific item
     */
    public function show($id)
    {
        $item = ItemModel::with('itemType')->find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json([
            'data' => $item,
            'image_url' => !empty($item->photo)
                ? asset($item->photo)  // Changed from 'image' to 'photo'
                : asset('images/no-image.png')
        ]);
    }

   /**
 * Update item details
 */
public function update(Request $request, $id)
{
    if (!$this->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $item = ItemModel::find($id);

    if (!$item) {
        return response()->json(['error' => 'Item not found'], 404);
    }

    // Modified validator to make fields optional for updates
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'price' => 'sometimes|numeric|min:0',
        'stock' => 'sometimes|integer|min:0',
        'item_type_id' => 'sometimes|exists:item_types,id',
        'image' => 'nullable|image|max:2048', // max 2MB
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        // Only update fields that are provided in the request
        if ($request->has('name')) {
            $item->name = $request->name;
        }
        
        if ($request->has('description')) {
            $item->description = $request->description;
        }
        
        if ($request->has('price')) {
            $item->price = $request->price;
        }
        
        if ($request->has('stock')) {
            $item->stock = $request->stock;
        }
        
        if ($request->has('item_type_id')) {
            $item->item_type_id = $request->item_type_id;
        }

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is not a default path
            if ($item->photo && !str_contains($item->photo, 'no-image.png')) {
                $oldImagePath = str_replace('storage/', 'public/', $item->photo);
                Storage::delete($oldImagePath);
            }

            $image = $request->file('image');
            $imageName = Str::slug($request->name ?? $item->name) . '_' . time() . '.' . $image->getClientOriginalExtension();

            // Store the file
            $path = $image->storeAs('public/items', $imageName);
            $item->photo = 'storage/items/' . $imageName;
        }

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui',
            'data' => $item
        ]);
    } catch (\Exception $e) {
        Log::error('Error updating item: ' . $e->getMessage());
        return response()->json([
            'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Delete an item
     */
    public function destroy($id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item = ItemModel::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        try {
            // Delete the image if it exists and is not a default path
            if ($item->photo && !str_contains($item->photo, 'no-image.png')) {
                // Remove the "storage/" prefix to correctly locate the file in Storage
                $oldImagePath = str_replace('storage/', 'public/', $item->photo);
                Storage::delete($oldImagePath);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting item: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
