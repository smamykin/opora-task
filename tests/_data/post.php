<?php

$faker = Faker\Factory::create();
$set = [];

for($i = 50; $i;--$i) {
    $set["post_$i"] = [
        'title' => implode(' ', $faker->words),
        'text' => $faker->paragraph,
        'author_id' => $i,
    ];
}

return $set;
