<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Method untuk resource "/"
    public function index()
    {
        return ('Selamat Datang');
    }

    // Method untuk resource "/about"
    public function about()
    {
        $nama = "Yonanda Mayla Rusdiaty";
        $nim = "2341760184";
        return "Nama: $nama <br> NIM: $nim";
    }

    // Method untuk resource "/articles/{id}"
    public function show($id)
    {
        return "Halaman Artikel dengan ID $id"; //diganti sesuai input url
    }

    // Method baru untuk resource "/articles" tanpa id spesifik
    public function articles()
    {
        return "Daftar Semua Artikel";
    }
}


