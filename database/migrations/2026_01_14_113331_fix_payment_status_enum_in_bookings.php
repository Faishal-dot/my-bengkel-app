<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kita gunakan query mentah (raw) agar lebih pasti mengubah tipe ENUM-nya
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'paid', 'failed') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        // Kembalikan ke asal jika diperlukan (sesuaikan dengan enum lama kamu jika tahu)
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status VARCHAR(255) DEFAULT 'unpaid'");
    }
};