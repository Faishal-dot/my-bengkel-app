<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q'); // Ambil keyword pencarian dari input 'q'

        $services = Service::query()
            ->when($query, function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->paginate(9); // 9 item per halaman

        return view('customer.services', compact('services'));
    }
}