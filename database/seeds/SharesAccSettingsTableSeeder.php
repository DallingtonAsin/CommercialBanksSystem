<?php

use Illuminate\Database\Seeder;

class SharesAccSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MillionsSaving\Models\Accounts\SharesAccSetting::class, 3)
        ->create();
    }
}
