<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class MechanicJobController extends Controller
{
    // Menampilkan pekerjaan mekanik yang sedang login
    public function index()
    {
        $mechanicId = Auth::id();

        $jobs = Job::where('mechanic_id', $mechanicId)->orderBy('created_at', 'desc')->get();

        return view('mechanic.jobs.index', compact('jobs'));
    }

    // Update status pekerjaan
    public function updateStatus(Job $job)
    {
        $this->authorize('update', $job); // opsional jika pakai policy

        $job->update([
            'status' => request('status')
        ]);

        return back()->with('success', 'Status pekerjaan berhasil diperbarui!');
    }
}