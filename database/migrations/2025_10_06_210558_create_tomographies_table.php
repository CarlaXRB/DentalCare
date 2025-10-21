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
        Schema::create('tomographies', function (Blueprint $table) {
            $table->id();
            $table->string('name_patient');
            $table->unsignedBigInteger('ci_patient');
            $table->unsignedBigInteger('tomography_id');
            $table->date('tomography_date');
            $table->string('tomography_type');
            $table->string('tomography_uri')->nullable();
            $table->string('tomography_dicom_uri')->nullable();
            $table->string('tomography_doctor');
            $table->string('tomography_charge');
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
        Schema::dropIfExists('tomographies');
    }
};
