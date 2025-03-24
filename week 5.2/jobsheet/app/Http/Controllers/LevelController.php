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
    // }0


    // menampilkan halaman tabel level:
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

    // menyediakan data level dalam format JSON untuk DataTables, termasuk filtering dan searching
    public function list(Request $request)
    {
        $levels = LevelModel::query();

        // Filter berdasarkan level_kode jika ada
        if ($request->has('level_kode') && $request->level_kode) {
            $levels->where('level_kode', $request->level_kode);
        }

        return DataTables::of($levels)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($level) {
                // Tambahkan tombol aksi (Detail, Edit, Hapus)
                $btn = '<a href="' . url("/level/{$level->level_id}") . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url("/level/{$level->level_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a> ';
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

    // menampilkan detail level
    public function show($id)
    {
        $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail level'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // Tambahkan variabel $level ke array data
        ]);
    }

    // Untuk menampilkan form edit level:
        public function edit($id)
        {
            $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID
        
            $breadcrumb = (object) [
                'title' => 'Edit Level',
                'list' => ['Home', 'Level', 'Edit']
            ];
        
            $page = (object) [
                'title' => 'Edit level'
            ];
        
            $activeMenu = 'level'; // Set menu yang sedang aktif
        
            return view('level.edit', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'activeMenu' => $activeMenu,
                'level' => $level // Kirim data level ke view
            ]);
        }

    // Untuk menyimpan perubahan data level
    public function update(Request $request, $id)
    {
        $request->validate([
            'level_nama' => 'required|string|max:100', // Validasi nama level
            'level_kode' => 'required|string|max:10', // Validasi kode level
        ]);
    
        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_nama' => $request->level_nama,
            'level_kode' => $request->level_kode
        ]);
    
        return redirect('/level')->with('success', 'Level berhasil diperbarui.');
    }

    // Untuk menghapus level
    public function destroy($id)
    {
        $level = LevelModel::findOrFail($id);
        $level->delete();

        return redirect('/level')->with('success', 'Level berhasil dihapus.');
    }

    // Untuk menampilkan form tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah level baru'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Untuk menyimpan data level baru
    public function store(Request $request)
    {
        $request->validate([
            'level_nama' => 'required|string|max:100', // Validasi nama level
            'level_kode' => 'required|string|max:10', // Validasi kode level
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Level berhasil ditambahkan.');
    }
}
