<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Home', 'Dashboard']
        ];

        $activeMenu = 'dashboard';
        $welcomeMessage = "Selamat Datang di Aplikasi POS!";

        return view('home', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'welcomeMessage' => $welcomeMessage
        ]);
    }
}