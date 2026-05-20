<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buttons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->string('name');

            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buttons');
    }
};
