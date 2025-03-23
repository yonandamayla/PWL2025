<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LevelModel; // Import the LevelModel class

class LevelController extends Controller
{
    // public function index()
    // {
    //     // DB::insert('insert into m_level (level_kode, level_nama, created_at) values (?, ?, ?)', ['CUS', 'Pelanggan', now()]);
    //     // return 'Insert data baru berhasil';

    //     // $row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
    //     // return 'Update data berhasil. Jumlah data yg diupdate: ' . $row;

    //     // $row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
    //     // return 'Delete data berhasil. Jumlah data yg dihapus: ' . $row . ' baris';

    //     $data = DB::select('select * from m_level');
    //     return view ('level', ['data' => $data]);
    // }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level pengguna'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_nama'); // Ambil data level

        return DataTables::of($levels)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($level) {
                // Tambahkan tombol aksi (Edit, Hapus)
                $btn = '<a href="' . url("/level/{$level->level_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url("/level/{$level->level_id}") . '">'
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
