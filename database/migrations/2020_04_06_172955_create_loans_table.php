<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name');
            $table->string('gender');
            $table->double('loan_amount');
            $table->double('duration');
            $table->string('duration_in');
            $table->string('collateral');
            $table->boolean('is_member')->default(false);
            $table->string('address');
            $table->string('occupation');
            $table->string('telno');
            $table->string('alt_telno')->nullable();
            $table->string('email')->nullable();
            $table->text('statement')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->binary('loanapp_file')->nullable();
            $table->timestamp('submitted_on')->useCurrent();
            $table->boolean('is_approved')->default(false);
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->boolean('is_denied')->default(false);
            $table->string('denied_by')->nullable();
            $table->timestamp('denied_on')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->date('taken_on')->nullable();
            $table->date('due_date')->nullable();
            $table->double('paid_amount')->nullable();
            $table->date('paid_on')->nullable();
             $table->string('received_by')->nullable();
            $table->double('loan_balance')->nullable();
          
            
          

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
