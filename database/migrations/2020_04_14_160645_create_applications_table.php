<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender');
            $table->string('address');
            $table->string('type');
            $table->string('occupation');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('company')->nullable();
            $table->string('tel_no');
            $table->string('alt_telno')->nullable();
            $table->date('date_of_birth');
            $table->string('email')->nullable();
            $table->string('app_file')->nullable();
             $table->timestamp('submitted_on');
            $table->boolean('is_approved')->default(false);
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->boolean('is_denied')->default(false);
            $table->string('denied_by')->nullable();
            $table->timestamp('denied_on')->nullable();
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
        Schema::dropIfExists('applications');
    }
}
