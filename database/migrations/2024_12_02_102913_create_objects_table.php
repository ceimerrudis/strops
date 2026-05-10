<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Objektu tabula. Objekts ir uzņēmuma projekts, piemēram, pievienot konkrētu māju elektrības tīklam.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(false)->unique();
            $table->string('name')->nullable(false);
            $table->boolean('active')->default(true);
            $table->dateTime('deleted_at')->nullable(true);
            $table->unsignedBigInteger('user_in_charge')->nullable(true);
            $table->foreign('user_in_charge')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};
