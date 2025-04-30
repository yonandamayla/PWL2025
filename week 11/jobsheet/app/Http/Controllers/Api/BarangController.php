<?php

namespace App\Http\Controllers\Api;

use App\Models\BarangModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Handle image upload and create new barang
     */
    public function upload(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'required|string|max:20|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('barang', 'public');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed',
            ], 400);
        }

        // Create barang with image
        $barang = BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
            'image' => $image->hashName(),
        ]);

        // Return response
        if ($barang) {
            return response()->json([
                'success' => true,
                'message' => 'Barang created successfully',
                'data' => $barang,
                'image_url' => $barang->image_url,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create barang',
        ], 409);
    }

    /**
     * Get barang data (with image_url)
     */
    public function getBarang($id = null)
    {
        if ($id) {
            $barang = BarangModel::with('kategori')->find($id);
            
            if (!$barang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang not found',
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $barang,
                'image_url' => $barang->image_url,
            ]);
        } else {
            $barang = BarangModel::with('kategori')->get();
            
            return response()->json([
                'success' => true,
                'data' => $barang,
            ]);
        }
    }
}