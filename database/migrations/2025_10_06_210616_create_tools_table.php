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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tool_radiography_id')->default(0);
            $table->unsignedBigInteger('tool_tomography_id')->default(0);
            $table->unsignedBigInteger('ci_patient')->default(0);
            $table->date('tool_date');
            $table->string('tool_uri');
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
        Schema::dropIfExists('tools');
    }
};
