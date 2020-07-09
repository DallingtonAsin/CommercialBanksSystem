<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('gender');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_role')->default(1);
            $table->string('occupation');
            $table->string('tel_no');
            $table->string('alt_telno')->nullable();
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('company')->nullable();
            $table->string('date_of_birth');
            $table->string('acc_name')->nullable();
            $table->string('acc_noM')->nullable();
            $table->string('acc_noE')->nullable();
            $table->string('acc_noR')->nullable();
            $table->string('acc_noS')->nullable();
            $table->string('pin', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->binary('image')->nullable();
            $table->string('password',255)->default(Hash::make('12345678'));
            $table->boolean('active')->default(false);
            $table->string('inactivated_by')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();
            $table->foreign('user_role')->references('role_id')->on('roles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
