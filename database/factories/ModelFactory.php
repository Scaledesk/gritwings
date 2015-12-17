<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('1234'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(\App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
        'slug' => $faker->slug(1),
    ];
});


$factory->define(\App\PackageType::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
    ];
});

$factory->define(\App\DeliveryType::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
    ];
});

$factory->define(\App\PaymentType::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
    ];
});

$factory->define(\App\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
        'slug' => ucfirst($faker->slug(1)),
    ];
});