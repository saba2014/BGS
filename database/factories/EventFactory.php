<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $date = Carbon::now();
    $start = (clone $date)->addDays(1);
    $end = (clone $date)->addDays(30);
    return [
        'name' => $faker->name,
        'date' =>$faker->dateTimeBetween($start, $end),
        'city' =>$faker->city
    ];
});
