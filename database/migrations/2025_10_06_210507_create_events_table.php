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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
                        $table->string('event');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('duration_minutes');
            $table->enum('room', ['Sala 1', 'Sala 2']);
            $table->string('details')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('assigned_doctor')->nullable();
            $table->unsignedBigInteger('assigned_radiologist')->nullable();
            $table->foreign('patient_id')->references('id')->on('patients')->nullOnDelete();
            $table->foreign('assigned_doctor')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_radiologist')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('events');
    }
};
