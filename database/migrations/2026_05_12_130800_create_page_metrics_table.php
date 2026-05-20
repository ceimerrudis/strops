<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->integer('visit_count')->default(0);
            $table->double('avg_load_time')->default(0.00);
            $table->double('max_load_time')->default(0.00);

            $table->foreign('page_id')->references('id')->on('pages');
            $table->foreign('device_id')->references('id')->on('device_info');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_metrics');
    }
};
