<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Kamu bisa tambahkan logika data di sini nanti (misalnya list booking, kendaraan, dll)
        return view('mechanic.dashboard');
    }
}