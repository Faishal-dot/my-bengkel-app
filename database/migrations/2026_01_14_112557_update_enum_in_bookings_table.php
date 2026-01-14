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
    // Menambahkan 'failed' ke dalam daftar pilihan yang diizinkan
    \DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'failed') DEFAULT 'unpaid'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
