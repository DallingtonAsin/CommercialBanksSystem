<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MillionsSaving\Models\Applications\Application;

$factory->define(Application::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		 'gender' =>'female',
		'address' =>$faker->streetName,
		'type' => 'membership',
		'city' => $faker->city,
		'state' => $faker->state,
		'zipcode' => $faker->postcode,
		'company' =>$faker->company,
		'occupation' => $faker->jobTitle,
		'tel_no' => $faker->e164PhoneNumber, 
		'date_of_birth' =>$faker->date($format = 'Y-m-d', $max = 'now'),
		'email' => $faker->freeEmail,
		'submitted_on' => $faker->dateTime($max = 'now', $timezone = 'Africa/Kampala'),
    ];
});
