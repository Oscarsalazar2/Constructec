<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'asc')->get();

        return view('dashboard', compact('products'));
    }

    public function home()
    {
        $products = Product::orderBy('id', 'asc')->get();

        return view('welcome', compact('products'));
    }

    public function getProducts()
    {
        return response()->json([
            'status' => 'success',
            'data' => Product::orderBy('id', 'asc')->get()
        ]);
    }
}
