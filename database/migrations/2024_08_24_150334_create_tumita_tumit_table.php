<?php

use App\Enums\TumitaTumitEnum;
use App\Models\TumitaTumit;
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
        Schema::create('tumita_tumits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('tumit_id');
            $table->string('status')->default(TumitaTumitEnum::PENDING->value);
            $table->timestamps();

            $table->index(['user_id','tumit_id']);

            $table->foreign('user_id')->references('id')->on('tumitas')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('tumit_id')->references('id')->on('tumits')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tumita_tumits', function (Blueprint $table) {
            //
            $table->dropForeign(['user_id','tumit_id']);
            $table->dropIndex(['user_id','tumit_id']);
        });
        Schema::dropIfExists('tumita_tumits');
    }
};
