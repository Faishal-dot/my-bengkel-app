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
    Schema::table('services', function (Blueprint $table) {
        // Kita letakkan setelah discount_price agar rapi
        $table->timestamp('discount_start')->nullable()->after('discount_price');
        $table->timestamp('discount_end')->nullable()->after('discount_start');
    });
}

public function down(): void
{
    Schema::table('services', function (Blueprint $table) {
        $table->dropColumn(['discount_start', 'discount_end']);
    });
}
};
