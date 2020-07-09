<?php

use Illuminate\Database\Seeder;

class RetirementAccSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MillionsSaving\Models\Accounts\RetirementAccSetting::class,
         3)->create();
    }
}
