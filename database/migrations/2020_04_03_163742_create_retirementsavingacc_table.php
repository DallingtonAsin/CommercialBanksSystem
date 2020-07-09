<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirementsavingaccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retirementsavingacc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id')->default(5);
            $table->string('acc_no');
            $table->string('acc_name');
            $table->string('type');
            $table->string('description');
             $table->double('deposit');
            $table->double('withdrawal');
            $table->double('balance');
            $table->timestamp('date')->useCurrent();
            $table->foreign('member_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retirementsavingacc');
    }
}
