<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('browser_name')->nullable();
            $table->string('os_name')->nullable();
            $table->integer('screen_width')->nullable();
            $table->integer('screen_height')->nullable();
            $table->timestamps();

            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        
        Schema::dropIfExists('device_info');
    }
};
