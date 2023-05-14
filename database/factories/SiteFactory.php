<?php

use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        'url' => $faker->url(),
    ];
});
