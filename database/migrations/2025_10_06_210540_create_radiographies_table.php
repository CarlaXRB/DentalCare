<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radiographies', function (Blueprint $table) {
            $table->id();
            $table->string('name_patient');
            $table->unsignedBigInteger('ci_patient');
            $table->unsignedBigInteger('radiography_id');
            $table->date('radiography_date');
            $table->string('radiography_type');
            $table->string('radiography_uri');
            $table->string('radiography_doctor');
            $table->string('radiography_charge');
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
        Schema::dropIfExists('radiographies');
    }
};
