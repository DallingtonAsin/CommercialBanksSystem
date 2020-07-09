<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MillionsSaving\Models\Accounts\SharesAccSetting;

$factory->define(SharesAccSetting::class, function (Faker $faker) {
    return [
         'setting_key' => $faker->text($maxNbChars = 5),
         'setting_value' => $faker->numberBetween($min=8000, $max=12000),
    ];
});
