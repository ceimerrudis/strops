<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Tabula lai uzskaitītu speciālās izņēmum situācijas kas radušās lietojot sistēmu.
Piezīme šī tabula nav saistīta ar strops sistēmas tehniskajiem defektiem.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error', function (Blueprint $table) {
            $table->id();
            $table->decimal('usage_before')->nullable(true)->default(0);
            $table->decimal('usage_after')->nullable(true)->default(0);
            $table->unsignedBigInteger('vehicle_use')->nullable(true);
            $table->foreign('vehicle_use')->references('id')->on('vehicle_uses');
            $table->unsignedBigInteger('reservation')->nullable(true);
            $table->foreign('reservation')->references('id')->on('reservations');
            $table->text('comment')->nullable(true);
            $table->dateTime('deleted_at')->nullable(true); 
            $table->dateTime('time')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error');
    }
};
