<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $tabel->string('name_kana')->nullable();
            $table->string('tel_1',11);
            $table->integer('treatment_flag');
            $table->text('introduction')->nullable();
            $table->string('line_user_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_staff');
    }
}
