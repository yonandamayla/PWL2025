<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Logika untuk menampilkan daftar produk
        return view('products.index');
    }
    public function foodBeverage()
    {
        return view("category.food-baverage");
    }

    public function babyKid()
    {
        return view("category.baby-kid");
    }

    public function beautyHealth()
    {
        return view("category.beauty-health");
    }

    public function homeCare()
    {
        return view("category.home-care");
    }
}
