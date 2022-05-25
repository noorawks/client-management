<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Organization;
use App\Models\User;

use Faker\Generator as Faker;

$usersIDs = User::get()->pluck('id');

$factory->define(Organization::class, function (Faker $faker) use ($usersIDs) {
    if ($usersIDs)
        return [
            'name' => 'Client ' . $this->faker->name(),
            'user_id' => $faker->randomElement($usersIDs->toArray()),
            'email' => $this->faker->unique()->safeEmail(),
            'phone'     => $this->faker->phoneNumber(),
            'logo' => 'https://source.unsplash.com/random'
        ];
});
