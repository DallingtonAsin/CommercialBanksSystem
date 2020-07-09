<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MillionsSaving\Models\Loans\LoanSetting;

$factory->define(LoanSetting::class, function (Faker $faker) {
    return [
        'min_loanamt' => $faker->numberBetween($min=8000, $max=12000),
        'max_loanamt' => $faker->numberBetween($min=8000, $max=12000),
        'interest_rate' => $faker->randomFloat($nbMaxDecimals = 1,
                                  $min = 1, $max = 20),
    ];
});
