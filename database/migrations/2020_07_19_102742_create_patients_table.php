<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('patient_name');
            $tabel->string('patient_name_kana')->nullable();
            $table->string('tel_1',11)->nullable();
            $table->string('tel_2',10)->nullable();
            $table->string('email')->nullable();
            $table->string('line_user_id')->nullable();
            $table->date('birthday')->nullable();
            $table->string('sex');
            $table->integer('insurance_class');
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('patients');
    }
}
