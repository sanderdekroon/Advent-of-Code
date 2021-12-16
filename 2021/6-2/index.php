<?php

$lines = array_filter(explode(',', file_get_contents(__DIR__ . '/input.txt')));

$fishes = array_map('intval', $lines);

$update = $states = array_pad([], 9, 0);

foreach ($fishes as $fish) {
    $states[$fish]++;
}

for ($i = 0; $i < 256; $i++) {
    foreach ($states as $state => $count) {
        if ($state === 7) {
            $count += $states[0];
        }

        if ($state === 0) {
            $update[8] = $count;
        } else {
            $update[$state - 1] = $count;
        }
    }


    $states = $update;
}

var_dump(array_sum($states));
