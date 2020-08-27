<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('tel',11)->nullable();
            $table->string('email')->nullable();
            $table->integer('mst_cource_id')->unsigned();
            $table->foreign('mst_cource_id')->references('id')->on('mst_cources');
            $table->integer('mst_staff_id')->unsigned();
            $table->foreign('mst_staff_id')->references('id')->on('mst_staff');
            $table->date('schedule_date');
            $table->integer('mst_status_id')->default(1);
            $table->foreign('mst_status_id')->references('id')->on('mst_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
