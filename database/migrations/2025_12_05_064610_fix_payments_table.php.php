<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('payments', function (Blueprint $table) {

        // Jika foreign key sudah ada → drop dulu
        $table->dropForeign(['booking_id']);

        // Pastikan type booking_id benar
        $table->unsignedBigInteger('booking_id')->change();

        // Tambahkan foreign key baru
        $table->foreign('booking_id')
              ->references('id')
              ->on('bookings')
              ->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
        });
    }
};