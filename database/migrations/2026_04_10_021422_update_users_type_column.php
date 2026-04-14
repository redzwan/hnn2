<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalise existing 'client' values to 'customer'
        DB::table('users')->where('type', 'client')->update(['type' => 'customer']);

        // Set admin@shop.dev-stage.net as admin
        DB::table('users')->where('email', 'admin@shop.dev-stage.net')->update(['type' => 'admin']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('type')->default('customer')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('type')->default('client')->change();
        });
    }
};
