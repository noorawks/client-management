<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Person;
use App\Models\Organization;
use Faker\Generator as Faker;

$organizationsIDs = Organization::get()->pluck('id');

$factory->define(Person::class, function (Faker $faker) use ($organizationsIDs) {
    if ($organizationsIDs)
        return [
            'name' => $this->faker->name(),
            'organization_id' => $faker->randomElement($organizationsIDs->toArray()),
            'email' => $this->faker->unique()->safeEmail(),
            'phone'     => $this->faker->phoneNumber(),
            'avatar' => 'https://source.unsplash.com/random'
        ];
});
