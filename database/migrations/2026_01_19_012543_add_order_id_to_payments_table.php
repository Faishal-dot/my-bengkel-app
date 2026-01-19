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
    Schema::table('payments', function (Blueprint $table) {
        // Tambahkan kolom order_id setelah booking_id
        // nullable() artinya boleh kosong (karena kalau bayar servis, order_id kosong)
        $table->unsignedBigInteger('order_id')->nullable()->after('booking_id');
        
        // Opsional: Jika Anda punya tabel orders, buat foreign key-nya
        // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn('order_id');
    });
}
};
