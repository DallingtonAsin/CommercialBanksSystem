<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MillionsSaving\Models\Accounts\EducationSaving;

$factory->define(EducationSaving::class, function (Faker $faker) {
    return [

    	'date' => $faker->dateTimeBetween($startDate = '-1 years',
           $endDate='now',$timezone='Africa/Kampala'),
    	'acc_no' => $faker->creditCardNumber,
    	'acc_name' => $faker->firstName,
        'type' => $faker->text($maxNbChars = 15),
        'description' =>$faker->text($maxNbChars = 30),
        'deposit' => $faker->numberBetween($min=10000, $max=50000),
        'withdrawal' => $faker->numberBetween($min=800, $max=15000),
        'balance' => $faker->numberBetween($min=5500, $max=9000),

        
    ];
});
