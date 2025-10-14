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
            // relasi ke services
            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained('services')
                  ->onDelete('set null');

            // tambahkan kolom vehicle & notes
            $table->string('vehicle')->after('service_id');
            $table->text('notes')->nullable()->after('vehicle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_id', 'vehicle', 'notes']);
        });
    }
};