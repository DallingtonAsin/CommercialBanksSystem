<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payer_accno')->nullable();
            $table->string('payer_accname')->nullable();
            $table->string('payee_accno');
            $table->string('payee_accname');
            $table->double('amount');
            $table->string('deposited_by');
            $table->string('payment_mode')->nullable();
            $table->string('branch')->nullable();
            $table->longText('reason');
            $table->string('telno');
            $table->timestamp('date');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
