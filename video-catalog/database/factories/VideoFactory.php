<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    $rating = array_rand(Video::RATING_LIST);
    return [
        'title' => $faker->colorName, 
        'description' => $faker->sentence(10), 
        'year_launched' => $faker->year(), 
        'opened' => rand(0, 1),
        'rating' => $rating, 
        'duration' => $faker->dayOfMonth(),
        // 'thumb_file' => null,
        // 'banner_file' => null,
        // 'trailer_file' => null,
        // 'video_file' => null,
        // 'published' => rand(0, 1),
    ];
});
