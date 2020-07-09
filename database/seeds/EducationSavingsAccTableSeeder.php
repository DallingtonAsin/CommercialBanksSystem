<?php

use Illuminate\Database\Seeder;

class EducationSavingsAccTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MillionsSaving\Models\Accounts\EducationSaving::class, 10)->create();
    }
}
