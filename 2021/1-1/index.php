<?php

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$previous = null;
$count = 0;

// Initial
foreach ($lines as $line) {
    if ($previous && $line > $previous) {
        $count++;
    }

    $previous = $line;
}

var_dump($count); // 1184


// Functional-ish
$previous = null;
$count = array_reduce($lines, function ($carry, $line) use (&$previous) {
    if ($previous && $line > $previous) {
        $carry++;
    }

    $previous = $line;

    return $carry;
}, 0);

var_dump($count);
