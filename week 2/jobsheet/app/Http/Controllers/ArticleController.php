<?php

namespace App\Http\Controllers;

class ArticleController extends Controller
{
    public function __invoke($id = null)
    {
        if ($id) {
            return "Halaman Artikel dengan Id $id";
        }
        return "Daftar Semua Artikel"; 
    }
}