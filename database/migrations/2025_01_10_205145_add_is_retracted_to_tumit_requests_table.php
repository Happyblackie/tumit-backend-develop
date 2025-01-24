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
        Schema::table('tumit_requests', function (Blueprint $table) {
            $table->integer('is_retracted')->defailt(0)->after('is_accepted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tumit_requests', function (Blueprint $table) {
            $table->dropColumn('is_retracted');
        });
    }
};
