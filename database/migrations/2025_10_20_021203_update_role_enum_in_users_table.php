<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Tambahkan 'customer' ke dalam array enum
        $table->enum('role', ['admin', 'user', 'mechanic', 'customer'])->default('user')->change();
    });
}

    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'user', 'mechanic'])->default('user')->change();
    });
}
};