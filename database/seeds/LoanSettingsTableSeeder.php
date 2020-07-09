<?php

use Illuminate\Database\Seeder;

class LoanSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         factory(MillionsSaving\Models\Loans\LoanSetting::class, 6)->create();
    }
}
