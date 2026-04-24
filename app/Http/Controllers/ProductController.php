<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return view('home');
    }

    public function getProducts() {
        return response()->json([
            'status' => 'success',
            'data' => Product::orderBy('id', 'asc')->get()
        ]);
    }
}
