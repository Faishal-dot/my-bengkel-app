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
    Schema::table('products', function (Blueprint $table) {
        // Menambahkan kolom category setelah kolom name (atau sesuaikan urutannya)
        // Kita gunakan string atau enum agar sesuai dengan pilihan di filter
        $table->string('category')->default('Product')->after('name');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('category');
    });
}
};
