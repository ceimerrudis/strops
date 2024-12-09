<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Šī tabula saglabā sīkdatnes kas tiks izmantotas lai identificētu ierīces,
kuras drīkst pievienoties sistēmai bez lietotājvārda un paroles pārbaudes.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remember_me_token', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user')->nullable(false);
            $table->foreign('user')->references('id')->on('users');
            $table->string('token');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('remember_me_token');
    }
};
