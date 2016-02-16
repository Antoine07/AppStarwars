<?php

use Carbon\Carbon;

function title(Faker\Generator $faker)
{
    $sentence = $faker->sentence(3);

    return substr($sentence, 0, strlen($sentence) - 1);
}

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    static $userId = 0;
    return [
        'user_id'        => ++$userId,
        'address'        => $faker->address,
        'number_card'    => $faker->creditCardNumber(),
        'number_command' => rand(1, 200),
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {

    $title = title($faker);

    return [
        'name'         => $title,
        'slug'         => str_slug($title),
        'category_id'  => rand(1, 2),
        'price'        => $faker->randomFloat(2, 20, 2000),
        'quantity'     => rand(2, 5),
        'abstract'     => $faker->paragraph(3),
        'content'      => $faker->paragraph(5),
        'published_at' => $faker->dateTime('now')
    ];
});

