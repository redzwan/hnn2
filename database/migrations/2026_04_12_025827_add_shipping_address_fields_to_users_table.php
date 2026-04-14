<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('default_city', 100)->nullable()->after('address');
            $table->string('default_state', 100)->nullable()->after('default_city');
            $table->string('default_zip', 20)->nullable()->after('default_state');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['default_city', 'default_state', 'default_zip']);
        });
    }
};
