<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{

    public function __invoke(Request $request)
    {
        $nama = "Yonanda Mayla Rusdiaty";
        $nim = "2341760184";
        return "Nama: $nama <br> NIM: $nim";
    }
}
