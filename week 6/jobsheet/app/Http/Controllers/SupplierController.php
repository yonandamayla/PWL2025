<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;

class SupplierController extends Controller
{
    // Method index untuk menampilkan halaman utama level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier';

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Method create untuk menampilkan form tambah supplier
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

    // Ambil data supplier dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'supplier_telp');

        return DataTables::of($suppliers)
            ->addIndexColumn() // menambahkan kolom index / no urut
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                $btn = '';
                // Tambahkan tombol Detail
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/show_ajax') . '\')" class="btn btn-info btn-sm mr-1">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mr-1">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // membuat dan menampilkan halaman form tambah supplier dgn Ajax
    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        Log::info('store_ajax called with data:', $request->all());

        if ($request->ajax()) {
            $rules = [
                'supplier_nama' => 'required|string|min:3|max:100',
                'supplier_alamat' => 'required|string|min:5',
                'supplier_telp' => 'required|string|min:5|max:20',
                'supplier_email' => 'nullable|email|max:100',
                'supplier_kontak' => 'nullable|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                // Generate kode supplier otomatis
                $lastSupplier = SupplierModel::orderBy('supplier_id', 'desc')->first();
                $lastKode = $lastSupplier ? intval(substr($lastSupplier->supplier_kode ?? 'SUP000', 3)) : 0;
                $newKode = 'SUP' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT);

                // Buat supplier baru
                $supplier = new SupplierModel();
                $supplier->supplier_kode = $newKode;
                $supplier->supplier_nama = $request->supplier_nama;
                $supplier->supplier_alamat = $request->supplier_alamat;
                $supplier->supplier_telp = $request->supplier_telp;

                // Set field opsional jika ada
                if ($request->filled('supplier_email')) {
                    $supplier->supplier_email = $request->supplier_email;
                }

                if ($request->filled('supplier_kontak')) {
                    $supplier->supplier_kontak = $request->supplier_kontak;
                }

                $supplier->save();

                Log::info('Supplier created successfully with ID: ' . $supplier->supplier_id);

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil ditambahkan'
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating supplier: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/supplier');
    }

    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            $rules = [
                'supplier_kode' => 'required|string|max:10',
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required',
                'supplier_telp' => 'required|string|max:20',
                'supplier_email' => 'nullable|email|max:100',
                'supplier_kontak' => 'nullable|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $supplier = SupplierModel::find($id);
                if (!$supplier) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data supplier tidak ditemukan'
                    ]);
                }

                $supplier->supplier_kode = $request->supplier_kode;
                $supplier->supplier_nama = $request->supplier_nama;
                $supplier->supplier_alamat = $request->supplier_alamat;
                $supplier->supplier_telp = $request->supplier_telp;
                $supplier->supplier_email = $request->supplier_email;
                $supplier->supplier_kontak = $request->supplier_kontak;
                $supplier->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil diperbarui'
                ]);
            } catch (\Exception $e) {
                Log::error('Error updating supplier: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/supplier');
    }

    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            try {
                $supplier = SupplierModel::find($id);
                if (!$supplier) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data supplier tidak ditemukan'
                    ]);
                }

                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                Log::error('Error deleting supplier: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/supplier');
    }

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }
}
