<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Tabula kas uztur darbinieku atskaites par objekta stadiju.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('object')->nullable(false);
            $table->foreign('object')->references('id')->on('objects');
            $table->decimal('progress')->nullable(false)->default(0);
            $table->dateTime('date')->nullable(false);
            $table->dateTime('deleted_at')->nullable(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
