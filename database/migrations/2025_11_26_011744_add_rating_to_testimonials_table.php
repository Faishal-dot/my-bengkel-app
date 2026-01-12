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
    Schema::table('testimonials', function (Blueprint $table) {
        $table->unsignedTinyInteger('rating')->default(5);
    });
}

public function down()
{
    Schema::table('testimonials', function (Blueprint $table) {
        $table->dropColumn('rating');
    });
}
};
