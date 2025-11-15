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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name_patient');
            $table->unsignedBigInteger('ci_patient')->unique();
            $table->date('birth_date');
            $table->enum('gender',['Masculino','Femenino']);
            $table->integer('patient_contact');
            $table->foreignId('clinic_id')->constrained();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edit_by')->nullable();
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
};
