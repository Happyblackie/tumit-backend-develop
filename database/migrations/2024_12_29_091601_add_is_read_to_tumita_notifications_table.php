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
        Schema::table('tumita_notifications', function (Blueprint $table) {
            //
            $table->integer('is_read')->defailt(0)->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tumita_notifications', function (Blueprint $table) {
            //
            $table->dropColumn('is_read');
        });
    }
};
