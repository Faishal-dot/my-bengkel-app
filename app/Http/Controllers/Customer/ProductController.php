<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. FITUR FILTER KATEGORI (DIPERBAIKI)
        if ($request->filled('category')) {
            // Gunakan whereRaw LOWER agar tidak peduli huruf besar atau kecil
            $query->whereRaw('LOWER(category) = ?', [strtolower($request->category)]);
        }

        // 2. FITUR PENCARIAN
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        // Ambil data terbaru
        $products = $query->latest()->paginate(9)->withQueryString();

        return view('customer.product', compact('products'));
    }

    public function show(Product $product)
    {
        return view('customer.product-show', compact('product'));
    }
}