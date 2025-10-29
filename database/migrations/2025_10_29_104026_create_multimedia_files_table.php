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
        Schema::create('multimedia_files', function (Blueprint $table) {
            $table->id();
            $table->string('name_patient');
            $table->unsignedBigInteger('ci_patient');
            $table->unsignedBigInteger('radiography_id');
            $table->date('radiography_date');
            $table->string('file_path');
            $table->string('file_type');
            $table->string('study_type');
            $table->unsignedBigInteger('size');
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
        Schema::dropIfExists('multimedia_files');
    }
};
