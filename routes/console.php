<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Contoh bawaan Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal hapus notifikasi otomatis (testing: 2 menit)
Schedule::call(function () {
    \DB::table('notifications')
        ->where('created_at', '<', now()->subMinutes(2))
        ->delete();
})->everyMinute();
