<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($id, $name)
    {
        return view('profile')->with('id', '2341760184')->with('name', 'Yonanda Mayla Rusdiaty');
    }
}
