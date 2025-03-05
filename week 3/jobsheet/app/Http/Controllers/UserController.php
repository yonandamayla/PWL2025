<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // tambah data user dengan eloquent model 
        $data = [
            'nama' => 'Pelanggan Pertama',
        ];
        // Akses model UserModel
        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }
}