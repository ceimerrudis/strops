<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        
            $table->foreign('user_id')->references('id')->on('users');
    
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_locations');
    }
};
