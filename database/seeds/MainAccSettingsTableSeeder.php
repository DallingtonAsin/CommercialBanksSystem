<?php

use Illuminate\Database\Seeder;

class MainAccSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MillionsSaving\Models\Accounts\MainAccSetting::class, 3)
        ->create();
    }
}
