<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Tabula Inventāriem. Kodā tie tiek saukti par vehicles par spīti tam ka daži inventāri nav transportlīdzekļi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->unique();
            $table->decimal('usage')->nullable(false);
            //Enumurators UsageTypes
            $table->integer('usage_type')->nullable(false);
            $table->dateTime('deleted_at')->nullable(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
