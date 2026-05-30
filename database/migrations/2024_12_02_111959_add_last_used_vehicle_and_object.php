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
            $table->unsignedBigInteger('last_used_vehicle')->nullable(true);
            $table->unsignedBigInteger('last_used_object')->nullable(true);
            $table->foreign('last_used_vehicle')->references('id')->on('vehicles');
            $table->foreign('last_used_object')->references('id')->on('objects');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_used_vehicle')) {
                $table->dropForeign(['last_used_vehicle']);
                $table->dropColumn('last_used_vehicle');
            }

            if (Schema::hasColumn('users', 'last_used_object')) {
                $table->dropForeign(['last_used_object']);
                $table->dropColumn('last_used_object');
            }
        });
    }
};
