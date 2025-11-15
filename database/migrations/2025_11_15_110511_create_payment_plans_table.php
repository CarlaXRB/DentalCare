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
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id');
            $table->string('name')->nullable();
            $table->integer('installments')->default(1);
            $table->decimal('amount_per_installment', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edit_by')->nullable();
            $table->timestamps();
            $table->foreign('budget_id')->references('id')->on('budgets')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_plans');
    }
};
