<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Šī migrācija izdalīta atsevišķi riņķveida atkarības dēļ.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('lastUsedVehicle')->nullable(true);
            $table->unsignedBigInteger('lastUsedObject')->nullable(true);
            $table->foreign('lastUsedVehicle')->references('id')->on('vehicles');
            $table->foreign('lastUsedObject')->references('id')->on('objects');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lastUsedVehicle');
            $table->dropColumn('lastUsedObject');
        });
    }
};
