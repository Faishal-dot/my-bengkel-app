<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kita gunakan DB Statement karena mengubah ENUM di Laravel butuh library tambahan 
        // atau perintah SQL mentah agar aman.
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'disetujui', 'proses', 'selesai', 'ditolak') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};