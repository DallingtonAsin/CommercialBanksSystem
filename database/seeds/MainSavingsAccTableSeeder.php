<?php

use Illuminate\Database\Seeder;

class MainSavingsAccTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       factory(MillionsSaving\Models\Accounts\MainSaving::class, 20)->create();
    }
}
