<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // File xxxx_create_payments_table.php
public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('booking_id');
        $table->string('method')->default('cash'); 
        $table->decimal('amount', 15, 2); // Menggunakan decimal agar mendukung .00
        $table->enum('status', ['pending', 'success', 'failed', 'unpaid'])->default('pending');
        $table->string('bank_name')->default('-');
        $table->string('account_number')->default('-');
        $table->string('account_holder')->default('-');
        $table->timestamps();

        $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
