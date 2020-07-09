<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMainaccountStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

       DB::statement("CREATE VIEW mainaccount_stats AS 
        SELECT date_format(`date`,'%M-%Y') AS `month_year`,
        year(`date`) AS `year`,month(`date`) AS `month`,
        monthname(`date`) AS `monthname`,
        sum(`deposit`) AS `savings`, sum(`withdrawal`) as withdrawals 
        FROM `mainsavingacc` GROUP BY month_year, year,month,monthname ORDER BY year,month DESC");

   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW mainaccount_stats');
    }
}
