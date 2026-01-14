<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vehicle');
            $table->date('booking_date');
            $table->text('notes')->nullable();
            // PERUBAHAN DI SINI: approved diganti disetujui
            $table->enum('status', ['pending', 'disetujui', 'rejected', 'proses', 'selesai'])->default('pending');
            $table->unsignedBigInteger('mechanic_id')->nullable(); // Pastikan kolom ini ada
            $table->integer('queue_number')->nullable(); // Pastikan kolom ini ada
            $table->timestamps();
            
            $table->foreign('mechanic_id')->references('id')->on('mechanics')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};