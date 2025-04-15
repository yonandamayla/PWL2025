<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard with statistics.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get total sales amount
        $totalSales = OrderModel::where('status', 'completed')->sum('total_price');
        
        // Get items with low stock (threshold of 5)
        $lowStockThreshold = 5; 
        $lowStockCount = ItemModel::where('stock', '<', $lowStockThreshold)->count();
        $lowStockItems = ItemModel::where('stock', '<', $lowStockThreshold)->take(5)->get();
        
        // Get today's transactions
        $todayTransactions = OrderModel::whereDate('created_at', Carbon::today())->count();
        
        // Get active users
        $activeUsers = User::count();
        
        return view('home', [
            'totalSales' => $totalSales,
            'lowStockCount' => $lowStockCount,
            'lowStockItems' => $lowStockItems,
            'todayTransactions' => $todayTransactions,
            'activeUsers' => $activeUsers,
            'welcomeMessage' => 'Selamat datang di BluePos!',
            'breadcrumb' => (object) [
                'list' => ['Home', 'Dashboard']
            ]
        ]);
    }
}