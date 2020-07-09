<?php

use Illuminate\Database\Seeder;

class RetirementSavingsAccTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(MillionsSaving\Models\Accounts\RetirementSaving::class, 10)->create();
    }
}
