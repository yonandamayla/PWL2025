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

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori produk'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $categories = KategoriModel::select('kategori_id', 'kategori_nama'); // Ambil data kategori

        return DataTables::of($categories)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($kategori) {
                // Tambahkan tombol aksi (Edit, Hapus)
                $btn = '<a href="' . url("/kategori/{$kategori->kategori_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a> ';
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
}
