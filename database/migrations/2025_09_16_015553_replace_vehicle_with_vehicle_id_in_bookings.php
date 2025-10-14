<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // Jangan tambah kolom lagi karena sudah ada
        if (!Schema::hasColumn('bookings', 'vehicle_id')) {
            $table->foreignId('vehicle_id')
                  ->nullable()
                  ->after('service_id');
        }

        // Tambahkan foreign key constraint jika belum ada
        $table->foreign('vehicle_id')
              ->references('id')
              ->on('vehicles')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeign(['vehicle_id']);
        // Jangan drop kolom kalau memang sudah dipakai
    });
}
};