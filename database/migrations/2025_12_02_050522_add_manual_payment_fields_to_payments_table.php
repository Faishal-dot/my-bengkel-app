<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('method');
            }
            if (!Schema::hasColumn('payments', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('payments', 'account_holder')) {
                $table->string('account_holder')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('payments', 'proof')) {
                $table->string('proof')->nullable()->after('account_holder');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};