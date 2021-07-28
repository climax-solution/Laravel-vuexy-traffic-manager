<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Redirect;
use Faker\Generator as Faker;

$factory->define(Redirect::class, function (Faker $faker) {
    return [
        'url' => $this->faker->url(),
        'active' => $this->faker->boolean(),
        'max_daily_hits' => $this->faker->numberBetween($min = 10, $max = 999),
    ];
});
