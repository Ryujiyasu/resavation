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
            $table->bigIncrements('id');
            $table->integer('mst_cource_id')->unsigned();
            $table->foreign('mst_cource_id')->references('id')->on('mst_cources');
            $table->integer('mst_staff_id')->unsigned();
            $table->foreign('mst_staff_id')->references('id')->on('mst_staff');
            $table->integer('mst_time_id')->unsigned();
            $table->foreign('mst_time_id')->references('id')->on('mst_times');
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
