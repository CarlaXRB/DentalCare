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
        Schema::create('dicoms', function (Blueprint $table) {
            $table->id();
                        $table->string('file_name')->nullable();
            $table->string('image_url')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('patient_id')->nullable();
            $table->string('modality')->nullable();
            $table->date('study_date')->nullable();
            $table->integer('rows')->nullable();
            $table->integer('columns')->nullable();
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('dicoms');
    }
};
