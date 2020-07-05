<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Participant;
use App\Event;
use Faker\Generator as Faker;

$factory->define(Participant::class, function (Faker $faker) {
    $events_ids_array = Event::where('id' ,'>' ,0)->pluck('id');
    return [
        'first_name'=>$faker->firstName,
        'last_name' =>$faker->lastName,
        'email'     =>$faker->unique()->safeEmail,
        'event_id'  =>$faker->randomElement($events_ids_array)
    ];
});
