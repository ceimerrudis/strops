<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Tabula lai uzksitītu rezervācijas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable(false);
            $table->unsignedBigInteger('vehicle')->nullable(false);
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('vehicle')->references('id')->on('vehicles');
            $table->dateTime('deleted_at')->nullable(true); 
            $table->dateTime('from')->nullable(false);
            $table->dateTime('until')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
