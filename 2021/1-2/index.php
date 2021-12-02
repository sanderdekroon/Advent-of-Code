<?php

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$previous = 0;
$count = 0;

$linesA = $lines;
$linesB = array_slice($lines, 1);
$linesC = array_slice($lines, 2);

for ($i = 0; $i < count($linesC); $i++) {
    if (! isset($linesA[$i], $linesB[$i], $linesC[$i])) {
        continue;
    }

    $sum = $linesA[$i] + $linesB[$i] + $linesC[$i];

    if ($previous && $sum > $previous) {
        $count++;
    }

    $previous = $sum;
}

var_dump($count);
