<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $welcomeMessage = "Selamat Datang di Aplikasi POS!";
        return view('home', compact('welcomeMessage'));
    }
}
