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
    // Kita tambahkan 'unpaid' ke dalam daftar pilihan status
    DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'unpaid', 'success', 'failed') DEFAULT 'pending'");
}

public function down(): void
{
    // Kembalikan ke pilihan awal jika rollback
    DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'success', 'failed') DEFAULT 'pending'");
}
};
