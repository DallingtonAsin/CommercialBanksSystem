<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        $this->call([

                UserTableSeeder::class,
                EducationSavingsAccTableSeeder::class,
                MainSavingsAccTableSeeder::class,
                RetirementSavingsAccTableSeeder::class,
                SharesSavingsAccTableSeeder::class,
                LoansTableSeeder::class,
                ApplicationsTableSeeder::class,
                MainAccSettingsTableSeeder::class,
                EducAccSettingsTableSeeder::class,
                SharesAccSettingsTableSeeder::class,
                RetirementAccSettingsTableSeeder::class,
                LoanSettingsTableSeeder::class,
                InvestmentsTableSeeder::class,

        ]);

    }


}
