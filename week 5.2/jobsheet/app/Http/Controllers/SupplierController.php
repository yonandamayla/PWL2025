<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
    
        $page = (object) [
            'title' => 'Daftar supplier'
        ];
    
        $activeMenu = 'supplier'; // Set menu yang sedang aktif
        
        // Ambil semua supplier untuk dropdown filter
        $suppliers = SupplierModel::all();
    
        return view('supplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'nullable|string|max:255',
            'supplier_telp' => 'nullable|string|max:20',
            'supplier_email' => 'nullable|email|max:100',
            'supplier_kontak' => 'nullable|string|max:100',
        ]);

        // Generate kode supplier otomatis
        $lastSupplier = SupplierModel::orderBy('supplier_id', 'desc')->first();
        $lastKode = $lastSupplier ? intval(substr($lastSupplier->supplier_kode, 3)) : 0;
        $newKode = 'SUP' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT);

        try {
            SupplierModel::create([
                'supplier_kode' => $newKode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
                'supplier_telp' => $request->supplier_telp,
                'supplier_email' => $request->supplier_email,
                'supplier_kontak' => $request->supplier_kontak,
            ]);

            return redirect('/supplier')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail supplier'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit supplier'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'nullable|string|max:255',
            'supplier_telp' => 'nullable|string|max:20',
            'supplier_email' => 'nullable|email|max:100',
            'supplier_kontak' => 'nullable|string|max:100',
        ]);

        $supplier = SupplierModel::findOrFail($id);

        try {
            $supplier->update([
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
                'supplier_telp' => $request->supplier_telp,
                'supplier_email' => $request->supplier_email,
                'supplier_kontak' => $request->supplier_kontak,
            ]);

            return redirect('/supplier')->with('success', 'Supplier berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = SupplierModel::findOrFail($id);
            $supplier->delete();

            return redirect('/supplier')->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/supplier')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get data for DataTables
     */
    public function list(Request $request)
    {
        $supplier = SupplierModel::query();
        
        // Filter berdasarkan supplier_kode jika ada
        if ($request->has('supplier_kode') && $request->supplier_kode) {
            $supplier->where('supplier_kode', $request->supplier_kode);
        }
    
        return DataTables::of($supplier)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($supplier) {
                // Tambahkan tombol aksi (Detail, Edit, Hapus)
                $btn = '<a href="' . url("/supplier/{$supplier->supplier_id}") . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url("/supplier/{$supplier->supplier_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url("/supplier/{$supplier->supplier_id}") . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>'
                    . '</form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan kolom aksi dirender sebagai HTML
            ->make(true);
    }
}