<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Tambahkan 'pending' ke dalam daftar yang diizinkan
    DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'unpaid', 'paid') DEFAULT 'unpaid'");
}

public function down(): void
{
    // Kembalikan ke asal jika diperlukan
    DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid'");
}
};
