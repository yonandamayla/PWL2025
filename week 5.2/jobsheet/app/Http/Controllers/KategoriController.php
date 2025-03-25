<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KategoriModel; // Import the KategoriModel class

class KategoriController extends Controller
{
    // public function index()
    // {
    //     // $data = [
    //     //     'kategori_kode' => 'SNK',
    //     //     'kategori_nama' => 'Snack/Makanan Ringan',
    //     //     'created_at' => now(), 
    //     // ];
    //     // DB::table('m_kategori')->insert($data);
    //     // return 'Insert data baru berhasil';

    //     // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update([
    //     //     'kategori_nama' => 'Camilan']);
    //     // return 'Update data berhasil. Jumlah data yg diupdate: ' .$row. ' baris';

    //     // $row= DB::table('m_kategori') -> where('kategori_kode', 'SNK')->delete();
    //     // return 'Delete data berhasil. Jumlah data yg dihapus: ' .$row. ' baris';

    //     $data = DB::table('m_kategori')->get();
    //     return view('kategori', ['data' => $data]);
    // }

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

    // Untuk menyediakan data kategori dalam format JSON untuk DataTables
    public function list(Request $request)
    {
        $kategori = KategoriModel::query();
    
        // Filter berdasarkan kategori_nama jika ada
        if ($request->has('kategori_nama') && $request->kategori_nama) {
            $kategori->where('kategori_nama', 'like', '%' . $request->kategori_nama . '%');
        }
    
        return DataTables::of($kategori)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($kategori) {
                // Tambahkan tombol aksi (Detail, Edit, Hapus)
                $btn = '<a href="' . url("/kategori/{$kategori->kategori_id}") . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url("/kategori/{$kategori->kategori_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url("/kategori/{$kategori->kategori_id}") . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>'
                    . '</form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan kolom aksi dirender sebagai HTML
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
}
