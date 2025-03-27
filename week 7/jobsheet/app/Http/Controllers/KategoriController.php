<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KategoriModel; // Import the KategoriModel class
use Illuminate\Support\Facades\Validator; // Import the Validator class

class KategoriController extends Controller
{
    // Untuk menampilkan halaman tabel kategor
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        // Ambil semua kategori untuk dropdown filter
        $kategoris = KategoriModel::select('kategori_nama')->distinct()->get();

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategoris' => $kategoris
        ]);
    }

    // Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        return DataTables::of($kategoris)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                $btn = ''; // Inisialisasi variabel $btn
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Untuk menampilkan form tambah kategori
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Untuk menyimpan data kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_nama' => 'required|string|max:100', // Validasi nama kategori
        ]);

        // Generate kode kategori otomatis
        $lastKategori = KategoriModel::orderBy('kategori_id', 'desc')->first(); // Ambil kategori terakhir
        $lastKode = $lastKategori ? intval(substr($lastKategori->kategori_kode, 3)) : 0; // Ambil angka terakhir dari kode
        $newKode = 'KTG' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT); // Buat kode baru dengan format KTG001, KTG002, dst.

        try {
            KategoriModel::create([
                'kategori_kode' => $newKode, // Simpan kode kategori
                'kategori_nama' => $request->kategori_nama, // Simpan nama kategori
            ]);

            return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Untuk menampilkan detail kategori
    public function show($id)
    {
        $kategori = KategoriModel::findOrFail($id); // Ambil data kategori berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori // Kirim data kategori ke view
        ]);
    }

    // Untuk menampilkan form edit kategori
    public function edit($id)
    {
        $kategori = KategoriModel::findOrFail($id); // Ambil data kategori berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori // Kirim data kategori ke view
        ]);
    }

    // Untuk menyimpan perubahan data kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_nama' => 'required|string|max:100', // Validasi nama kategori
        ]);

        $kategori = KategoriModel::findOrFail($id);
        $kategori->update([
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Untuk menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        $kategori->delete();

        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus.');
    }

    // menampilkan halaman form tambah kategori dengan ajax
    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    // menyimpan data kategori baru dengan ajax
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|min:3|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors() // pesan error validasi
                ]);
            }

            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    // menampilkan halaman form edit kategori dengan ajax
    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    // menyimpan perubahan data kategori dengan ajax
    public function update_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|min:3|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = KategoriModel::find($id);

            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    // menampilkan konfirmasi hapus kategori dengan ajax
    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    // menghapus data kategori dengan ajax
    public function delete_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                try {
                    $kategori->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terkait dengan data lain'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}
