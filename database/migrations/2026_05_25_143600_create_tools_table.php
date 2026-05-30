<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('imgpath')->nullable();
            $table->unsignedBigInteger('default_loc')->nullable();
            $table->unsignedBigInteger('current_loc')->nullable();

            $table->foreign('default_loc')->references('id')->on('tool_locations');
            $table->foreign('current_loc')->references('id')->on('tool_locations');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
