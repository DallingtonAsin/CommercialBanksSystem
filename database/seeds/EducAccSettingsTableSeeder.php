<?php

use Illuminate\Database\Seeder;

class EducAccSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MillionsSaving\Models\Accounts\EducAccSetting::class, 3)->create();
    }
}
