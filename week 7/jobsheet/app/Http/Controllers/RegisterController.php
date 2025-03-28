<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // Ambil level untuk dropdown (level 4 = customer)
        $level = LevelModel::where('level_id', 4)->first();
        return view('auth.register', compact('level'));
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:100|unique:m_user',
            'nama' => 'required|string|max:100',
            'password' => 'required|string|min:5|confirmed',
        ], [
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'nama.required' => 'Nama harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 5 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Hitung user_id tertinggi
        $maxId = UserModel::max('user_id');
        $nextId = $maxId + 1;

        // Buat user baru (default level 4 = customer)
        $user = new UserModel();
        $user->user_id = $nextId;
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = 4; // Customer
        $user->save();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silahkan login.');
    }
}