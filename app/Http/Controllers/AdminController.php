<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/login');
        }

        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total') ?? 0;
        $recentProducts = Product::orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalOrders', 'totalRevenue', 'recentProducts'));
    }
}
