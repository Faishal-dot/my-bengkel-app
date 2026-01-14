<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('services', function (Blueprint $table) {
        // Tambahkan kolom discount_price (boleh kosong/nullable)
        $table->decimal('discount_price', 12, 2)->nullable()->after('price');
    });
}

public function down()
{
    Schema::table('services', function (Blueprint $table) {
        $table->dropColumn('discount_price');
    });
}

};
