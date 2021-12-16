<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));
$lines = array_map(fn($line) => str_split($line), $lines);

$lowestNumbers = [];
foreach ($lines as $rowNo => $line) {
    foreach ($line as $columnNo => $number) {
        $right = $line[$columnNo + 1] ?? null;

        $left = $line[$columnNo - 1] ?? null;

        $under = $lines[$rowNo + 1][$columnNo] ?? null;

        $over = $lines[$rowNo - 1][$columnNo] ?? null;

        if (
            ($right === null || $number < $right) &&
            ($left === null || $number < $left) &&
            ($under === null || $number < $under) &&
            ($over === null || $number < $over)
        ) {
            $lowestNumbers[] = $number + 1;
        }
    }
}

var_dump(array_sum($lowestNumbers));
