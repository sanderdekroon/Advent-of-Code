<?php

$lines = explode(',', file_get_contents(__DIR__ . '/input.txt'));
$crabs = array_map('intval', $lines);

$max = max($crabs);
$pointer = min($crabs);
$results = array_pad([], $max + 1, 0);


while ($pointer <= $max) {
    foreach ($crabs as $position) {
        if ($pointer === $position) {
            continue;
        }

        $factor = ($pointer < $position) ? ($position - $pointer) : ($pointer - $position);

        $results[$pointer] += $factor;
    }

    $pointer++;
}

asort($results);

var_dump($results);
