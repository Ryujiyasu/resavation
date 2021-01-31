<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstCourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_cources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('picture')->nullable();
            $table->string('name');
            $table->text('detail')->nullable();
            $table->string('color');
            $table->string('number_frames');
            $table->integer('price');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_cources');
    }
}
