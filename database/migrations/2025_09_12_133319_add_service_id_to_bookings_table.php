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
    Schema::table('bookings', function (Blueprint $table) {

        // relasi service
        if (!Schema::hasColumn('bookings', 'service_id')) {
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
        }

        // Hapus: $table->string('vehicle')
        // karena sekarang pakai vehicle_id

        if (!Schema::hasColumn('bookings', 'notes')) {
            $table->text('notes')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        if (Schema::hasColumn('bookings', 'service_id')) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        }

        if (Schema::hasColumn('bookings', 'notes')) {
            $table->dropColumn('notes');
        }
    });
}
};