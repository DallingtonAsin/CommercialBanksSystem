<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTopsaversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::statement("CREATE VIEW education_topsavers as
          select acc_no as accountNo,
          acc_name as name,
          sum(`deposit`) as savings 
          from `educationsavingacc` group by acc_no,acc_name
          order by sum(`deposit`) desc limit 5");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::statement('DROP VIEW education_topsavers');
    }
}
