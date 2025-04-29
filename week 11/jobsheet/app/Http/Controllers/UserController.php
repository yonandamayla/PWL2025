<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
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

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = UserModel::select('*');

            // Apply level filter if selected
            if ($request->filled('filter_level')) {
                $data->where('level_id', $request->filter_level);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '';
                    // Tambahkan tombol Detail dengan style yang sama
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $row->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm mr-1">Detail</button> ';
                    // Edit button with yellow background (warning class)
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $row->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mr-1">Edit</button> ';
                    // Delete button via modal
                    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $row->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
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

    // Menyimpan data user baru dengan ajax
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get(); // ambil data level untuk ditampilkan di form

        return view('user.create_ajax', ['level' => $level]);
    }

    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors() // pesan error validasi
                ]);
            }

            UserModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    // menampilkan halaman form edit user dengan ajax
    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required||max:100',
                'password' => 'nullable|min:6|max:20'
            ];

            $validator = Validator::make($request->all(), $rules);

            $check = UserModel::find($id);

            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }

                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
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

    // menampilkan konfirmasi hapus user dengan ajax
    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    // menghapus data user dengan ajax
    public function delete_ajax(Request $request, string $id)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
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

    public function import()
    {
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $row => $value) {
                    if ($row > 1) { // Skip header row
                        $insert[] = [
                            'user_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'level_id' => $value['D'],
                            'password' => bcrypt('password'), // Default password; adjust as needed
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                }
            }
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
        return redirect('/');
    }

    public function export_excel()
    {
        // Get user data to export with level relationship
        $users = UserModel::with('level')
            ->select('user_id', 'username', 'nama', 'level_id')
            ->orderBy('user_id')
            ->get();

        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID User');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Nama Lengkap');
        $sheet->setCellValue('E1', 'Level');

        // Make headers bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Populate data
        $no = 1;
        $baris = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $user->user_id);
            $sheet->setCellValue('C' . $baris, $user->username);
            $sheet->setCellValue('D' . $baris, $user->nama);
            $sheet->setCellValue('E' . $baris, $user->level->level_nama ?? ''); // Use level relationship
            $baris++;
            $no++;
        }

        // Auto-size columns
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set sheet title
        $sheet->setTitle('Data User');

        // Create writer and set filename
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set headers for file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        // Get user data to export with level relationship
        $users = UserModel::with('level')
            ->select('user_id', 'username', 'nama', 'level_id')
            ->orderBy('user_id')
            ->orderBy('level_id')
            ->get();

        // use Barryvdh\DomPDF\Facade\PDF
        $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);
        $pdf->setPaper('a4', 'portrait'); // Set paper size and orientation
        $pdf->setOption("isRemoteEnabled", true); // Enable remote images
        $pdf->render();

        return $pdf->stream('Data User' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Show the user profile page
     */
    public function profile()
    {
        // Use Laravel's auth system to get the current user
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // Define breadcrumb as an OBJECT (note the (object) cast)
        $breadcrumb = (object) [
            'title' => 'Profile User',
            'list' => ['Home', 'Profile']
        ];

        // Define page information
        $page = (object) [
            'title' => 'Profil Pengguna'
        ];

        // Set active menu
        $activeMenu = 'profile';

        return view('user.profile', compact('user', 'breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Update user profile photo
     */
    public function updatePhoto(Request $request)
    {
        // Validasi file
        $request->validate([
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Get the authenticated user
            $user = auth()->user();

            if (!$user) {
                return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
            }

            // Get the user ID
            $userId = $user->user_id;

            // Get the proper UserModel instance
            $userModel = UserModel::find($userId);

            if (!$userModel) {
                return redirect('/login')->with('error', 'User tidak ditemukan');
            }

            // Check if file exists
            if (!$request->hasFile('foto_profile') || !$request->file('foto_profile')->isValid()) {
                return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan');
            }

            // Delete old photo if exists
            if ($userModel->foto_profile && Storage::disk('public')->exists($userModel->foto_profile)) {
                Storage::disk('public')->delete($userModel->foto_profile);
            }

            // Store new photo with improved error handling
            $file = $request->file('foto_profile');
            $fileName = 'profile_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Log file information for debugging
            Log::info('Profile upload: ' . $fileName);

            $path = $file->storeAs('profiles', $fileName, 'public');
            Log::info('Stored path: ' . $path);

            // Verify that the file was stored successfully
            if (!Storage::disk('public')->exists($path)) {
                Log::error('Storage verification failed for: ' . $path);
                return redirect()->back()->with('error', 'Gagal menyimpan file ke storage');
            }

            // Update user record using update() method
            $updated = UserModel::where('user_id', $userId)->update([
                'foto_profile' => $path
            ]);

            if (!$updated) {
                Storage::disk('public')->delete($path); // Clean up if DB update fails
                Log::error('Database update failed for user: ' . $userId);
                return redirect()->back()->with('error', 'Gagal memperbarui data profil');
            }

            // Force refresh of user data
            $userModel->refresh();
            Log::info('New profile path: ' . $userModel->foto_profile);

            // Refresh user session data
            auth()->setUser(UserModel::find($userId));

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Profile upload exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }
}
