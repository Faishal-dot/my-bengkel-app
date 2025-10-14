<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Tampilkan semua produk untuk customer
    public function index(Request $request)
    {
        $query = Product::query();

        // Fitur pencarian
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
        }

        // Pagination, 9 per halaman
        $products = $query->paginate(9)->withQueryString();

        return view('customer.product', compact('products'));
    }

    // Optional: halaman detail produk
    public function show(Product $product)
    {
        return view('customer.product-show', compact('product'));
    }
}