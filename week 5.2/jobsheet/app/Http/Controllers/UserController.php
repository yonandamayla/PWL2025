<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // public function index()
    // {
    // tambah data user dengan eloquent model 
    // $data = [
    //     'nama' => 'Pelanggan Pertama',
    // ];

    // Akses model UserModel
    // $user = UserModel::all();
    // return view('user', ['data' => $user]);


    // $data = [
    //     'level_id' => 2,
    //     'username' => 'manager_dua',
    //     'nama' => 'Manager 2',
    //     'password' => Hash::make('12345'),
    // ];
    // UserModel::create($data);

    // $user = UserModel::all();
    // return view('user', ['data' => $user]);

    // $data = [
    //     'level_id' => 2,
    //     'username' => 'manager_tiga',
    //     'nama' => 'Manager 3',
    //     'password' => Hash::make('12345'),
    // ];
    // UserModel::create($data); // Perbaiki penulisan metode create

    // $user = UserModel::all();
    // return view('user', ['data' => $user]);

    // $user = UserModel::find(1);
    // return view ('user', ['data' => $user]);

    // $user = Usermodel::where('level_id', 1)->first();
    // return view('user', ['data' => $user]);

    // $user = UserModel::firstWhere('level_id', 1);
    // return view('user', ['data' => $user]);

    // $user = UserModel::findOr(1, ['username', 'nama'],function() {abort(404);});
    // return view('user', ['data' => $user]);

    // $user = UserModel::findOr(20, ['username', 'nama'], function () {
    //     abort(404);
    // });
    // return view('user', ['data' => $user]);

    // $user = UserModel::findOrFail(1);
    // return view('user', ['data' => $user]);

    // $user = UserModel::where('username', 'manager9') -> firstOrFail();
    // return view('user', ['data' => $user]);

    // $user = UserModel::where('level_id', 2)-> count();
    // // dd($user);
    // return view('user', ['data' => $user]);

    // $user = UserModel::firstOrCreate(
    //     ['username' => 'manager'],
    //     ['nama' => 'Manager 1']
    // );
    // return view('user', ['data' => $user]);

    // $user = UserModel::firstOrNew(
    //     [
    //     'username' => 'manager22',
    //     'nama' => 'Manager Dua Dua',
    //     'password' => Hash::make('12345'),
    //     'level_id' => 2
    //     ],
    // );
    // return view('user', ['data' => $user]);

    // $user = UserModel::firstOrNew(
    //     [
    //     'username' => 'manager',
    //     'nama' => 'Manager'
    //     ],
    // );
    // return view('user', ['data' => $user]);

    // $user = UserModel::firstOrNew(
    //     [
    //     'username' => 'manager33',
    //     'nama' => 'Manager Tiga Tiga',
    //     'password' => Hash::make('12345'),
    //     'level_id' => 2
    //     ],
    // );
    // $user->save();
    // return view('user', ['data' => $user]);

    // $user = UserModel::create([
    //     'username' => 'manager55',
    //     'nama' => 'Manager55',
    //     'password' => Hash::make('12345'),
    //     'level_id' => 2
    // ]);

    // $user -> username = 'manager56';

    // $user -> isDirty(); //true
    // $user -> isDirty('username'); //true
    // $user -> isDirty('nama'); //false
    // $user -> isDirty('nama', 'username'); //true

    // $user -> isClean(); //false
    // $user -> isClean('username'); //false
    // $user -> isClean('nama'); //true
    // $user -> isClean('nama', 'username'); //false

    // $user -> save();

    // $user -> isDirty(); //false
    // $user -> isClean(); //true 
    // dd($user ->isDirty());

    //     $user = UserModel::create([
    //         'username' => 'manager11',
    //         'nama' => 'Manager11',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2
    //     ]);

    //     $user -> username = 'manager12';
    //     $user -> save();

    //     $user -> wasChanged(); //true
    //     $user -> wasChanged('username'); //true
    //     $user -> wasChanged('username', 'level_id'); //teue
    //     $user -> wasChanged('nama', 'username'); //true
    //     dd($user -> wasChanged(['username', 'nama']));//true

    // $user = UserModel::all();
    // return view('user', ['data' => $user]);

    // $users = UserModel::with('level')->get();
    // return view('user', ['data' => $users]);

    // menampilkan halaman awal user
    //     $breadcrumb = (object) [
    //         'title' => 'Daftar User',
    //         'list' => ['Home', 'User']
    //     ];

    //     $page = (object) [
    //         'title' => 'Daftar user yang terdaftar dalam sistem'
    //     ];

    //     $activeMenu = 'user'; // set menu yang sedang aktif
    //     return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    // }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user');
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan(Request $request, $id)
    {
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

    public function list(Request $request)
    {
        // Ambil data user dengan relasi level
        $users = UserModel::with('level')->select('user_id', 'username', 'nama', 'level_id');
    
        // Filter berdasarkan level_id jika ada
        if ($request->has('level_id') && $request->level_id) {
            $users->where('level_id', $request->level_id);
        }
    
        // Kembalikan data dalam format DataTables
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level_nama', function ($user) {
                // Pastikan relasi level ada sebelum mengakses nama level
                return $user->level ? $user->level->level_nama : '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn = '<a href="' . url("/user/{$user->user_id}") . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url("/user/{$user->user_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url("/user/{$user->user_id}") . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>'
                    . '</form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // menampilkan halaman form tambah user baru
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // tambahkan variabel $level ke array data
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            // nama harus diisi, berupa string, dan maksimal 100 karakter
            'nama' => 'required|string|max:100',
            // password harus diisi dan minimal 5 karakter
            'password' => 'required|min:5',
            // level_id harus diisi dan berupa angka
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            // password dienkripsi sebelum disimpan
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // menampilkan detail user
    public function show($id)
    {
        $user = UserModel::with('level')->find($id); // ambil data user berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'user' => $user // tambahkan variabel $user ke array data
        ]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            // nama harus diisi, berupa string, dan maksimal 100 karakter
            'nama' => 'required|string|max:100',
            // password nullable (boleh kosong), minimal 5 karakter dan bisa tidak diisi
            'password' => 'nullable|min:5',
            // level_id harus diisi dan berupa angka
            'level_id' => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id); // Hapus data level
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
