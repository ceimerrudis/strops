<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('button_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('button_id')->nullable();
            $table->integer('press_count')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('button_id')->references('id')->on('buttons');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('button_clicks');
    }
};
