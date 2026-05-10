<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Tabula lietojumiem. Šī tabula ir galvenā visā strops sistēmā. 
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable(false);
            $table->unsignedBigInteger('vehicle')->nullable(false);
            $table->unsignedBigInteger('object')->nullable(false);
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('vehicle')->references('id')->on('vehicles');
            $table->foreign('object')->references('id')->on('objects');
            $table->text('comment')->nullable(true);
            $table->dateTime('deleted_at')->nullable(true); 
            $table->dateTime('from')->nullable(false);
            $table->dateTime('until')->nullable(true);
            $table->decimal('usage_before')->nullable(false)->default(0);
            $table->decimal('usage_after')->nullable(true)->default(0);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('vehicle_uses');
    }
};
