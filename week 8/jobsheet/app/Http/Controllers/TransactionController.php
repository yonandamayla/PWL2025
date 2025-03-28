<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = [
            ['product' => 'Nasi Goreng', 'qty' => 2, 'price' => 25000, 'total' => 50000],
            ['product' => 'Ayam Geprek', 'qty' => 3, 'price' => 20000, 'total' => 60000],
            ['product' => 'Mie Ayam', 'qty' => 1, 'price' => 18000, 'total' => 18000],
            ['product' => 'Es Teh Manis', 'qty' => 2, 'price' => 5000, 'total' => 10000],
            ['product' => 'Bakso', 'qty' => 4, 'price' => 22000, 'total' => 88000],
        ];

        return view('transaction')->with('transactions', $transactions);
    }
}
