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
        Schema::create('tumita_activities', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('user_id');            
            $table->string('action');
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id')->references('id')->on('tumitas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tumita_activities', function (Blueprint $table) {
            //
            $table->dropForeign('user_id');
            $table->dropIndex('user_id');
        });
        Schema::dropIfExists('tumita_activities');
    }
};
