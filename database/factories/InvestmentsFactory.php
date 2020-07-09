<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MillionsSaving\Models\Benefits\Investment;

$factory->define(Investment::class, function (Faker $faker) {
    return [
        'asset' => $faker->text($maxNbChars = 5),
        'details' =>  $faker->text($maxNbChars = 15),
        'capital' => $faker->numberBetween($min=1000000, $max=5000000),
        'returns' => $faker->numberBetween($min=80000, $max=200000),
        'approved_on' => $faker->date($format='Y-m-d', $max='now'),
        'approved_by' => $faker->firstName,
    ];
});
