<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function create()
    {
        return view('customer.testimoni.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|min:5',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'rating'  => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Testimoni berhasil dikirim!');
    }
}