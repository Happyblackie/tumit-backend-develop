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
        Schema::create('tumit_invites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tumit_id');            
            $table->string('phone_number');
            $table->string('name');
            $table->integer('is_cancelled');
            $table->timestamps();

            $table->index('tumit_id');

            $table->foreign('tumit_id')->references('id')->on('tumits')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tumit_invites', function (Blueprint $table) {
            //
            $table->dropForeign('tumit_id');
            $table->dropIndex('tumit_id');
        });
        Schema::dropIfExists('tumit_invites');
    }
};
