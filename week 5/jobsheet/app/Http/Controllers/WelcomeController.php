<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class WelcomeController extends Controller
{
    public function hello()
    {
        return view('blog.hello')
            ->with('name', 'Andi')
            ->with('occupation', 'Programmer');
    }
}