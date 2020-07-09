<?php

use Illuminate\Database\Seeder;

class SharesSavingsAccTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MillionsSaving\Models\Accounts\SharesSaving::class, 10)->create();
    }
}
