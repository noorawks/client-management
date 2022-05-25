<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;

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

$roleId = 2;

$factory->define(User::class, function (Faker $faker) use ($roleId) {
    if ($roleId)
        return [
            'name' => $faker->name,
            'role_id' => $roleId,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // password
            'remember_token' => Str::random(10),
        ];
});
