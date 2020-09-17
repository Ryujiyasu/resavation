<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('mst_time_id')->unsigned();
            $table->foreign('mst_time_id')->references('id')->on('mst_times');
            $table->integer('mst_staff_id')->unsigned();
            $table->foreign('mst_staff_id')->references('id')->on('mst_staff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_schedules');
    }
}
