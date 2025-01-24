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
        Schema::create('tumit_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tumit_id');  
            $table->unsignedBigInteger('tumita_id');        
            $table->integer('is_rejected');
            $table->integer('is_accepted');
            $table->timestamps();

            $table->index('tumit_id');
            $table->index('tumita_id');

            $table->foreign('tumit_id')->references('id')->on('tumits')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tumita_id')->references('id')->on('tumitas')->onDelete('cascade')->onUpdate('cascade');
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

            $table->dropForeign('tumita_id');
            $table->dropIndex('tumita_id');
        });
        Schema::dropIfExists('tumit_requests');
    }
};
