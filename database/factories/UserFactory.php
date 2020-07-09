<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use MillionsSaving\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(MillionsSaving\User::class, function (Faker $faker) {
  return [
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,
    'name' => $faker->name,
    'username' => $faker->unique()->lastName,
    'gender' => 'Male',
    'email' => $faker->unique()->safeEmail,
    'occupation' => $faker->jobTitle,
    'tel_no' => $faker->e164phoneNumber,
    'alt_telno' => $faker->e164phoneNumber,
    'address' => $faker->state,
    'city' => $faker->city,
    'state' => $faker->state,
    'zipcode' => $faker->postcode,
    'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
    'acc_name' => $faker->firstName,
    'acc_noM' => $faker->creditCardNumber,
    'email_verified_at' => now(),
    'image' => NULL,
    'password' => Hash::make(Str::random(8)),
    'inactivated_by' => $faker->firstName,
    'remember_token' => Str::random(10),
  ];
});
