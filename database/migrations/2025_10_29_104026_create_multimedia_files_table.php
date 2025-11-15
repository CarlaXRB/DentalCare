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
            $table->string('ci_patient');
            $table->string('study_code')->unique();
            $table->date('study_date')->nullable();
            $table->string('study_type');
            $table->text('study_uri');
            $table->string('description')->nullable();
            $table->integer('image_count')->default(0);
            $table->foreignId('clinic_id')->constrained();
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
