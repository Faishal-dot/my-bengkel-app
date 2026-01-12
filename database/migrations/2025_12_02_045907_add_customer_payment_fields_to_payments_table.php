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
    Schema::table('payments', function (Blueprint $table) {
        $table->string('bank_name')->nullable();
        $table->string('account_number')->nullable();
        $table->string('account_holder')->nullable();
        $table->string('proof')->nullable();
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn([
            'bank_name',
            'account_number',
            'account_holder',
            'proof'
        ]);
    });
}
};
