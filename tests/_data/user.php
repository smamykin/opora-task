<?php

$faker = Faker\Factory::create();
$set = [];

for($i = 50; $i--;) {
    $set["user_$i"] = [
        'name' => $faker->name,
    ];
}

return $set;
