<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MillionsSaving\Models\Loans\Loan;

$factory->define(Loan::class, function (Faker $faker) {
	return [
        'name' => $faker->firstName,
        'gender' => 'male',
		'loan_amount' => $faker->numberBetween($min=1000, $max=5000),
        'duration' => $faker->randomFloat($nbMaxDecimals = 1, $min = 1, $max=10),
        'duration_in' => 'years',
		'collateral' => $faker->text($maxNbChars = 6),
		'address' => $faker->streetName,
		'occupation' => $faker->jobTitle,
		'telno' =>$faker->e164PhoneNumber,
		'statement' => $faker->text($maxNbChars = 5) ,
		'date_of_birth' => $faker->date($format='Y-m-d', $max='now'),
		'loanapp_file' => $faker->file($sourceDir ="public/images/",
		 $targetDir = "public/images/icon/"),

	];
});
