<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\VinjetTypes;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->integer('vinjet')->nullable(false)->default(VinjetTypes::NONE);
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'vinjet')) {
                $table->dropColumn('vinjet');
            }
        });
    }
};
