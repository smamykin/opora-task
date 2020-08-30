<?php

$faker = Faker\Factory::create();
$set = [];
$now = new DateTimeImmutable();
$date = $now->sub(DateInterval::createFromDateString('10 days'));
$count = 0;
while ($date < $now) {
    for($i = 50; $i; --$i) {
        if (!mt_rand(0,100)) {
            $set['post_view_' . $count++ ] = [
                'post_id' => $i,
                'created_at' => $date->format('Y-m-d H:i:s'),
            ];
        }
    }

    $date = $date->add(DateInterval::createFromDateString('5 minutes'));
}

return $set;
