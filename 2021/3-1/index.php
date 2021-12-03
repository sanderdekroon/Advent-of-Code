<?php

$lines = array_map(function ($item) {
    return str_split($item);
}, array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt'))));

for ($i = 0; $i < 12; $i++) {
    $bit = round(array_sum(array_column($lines, $i)) / count($lines));
    $gammaRate = ($gammaRate ?? '') . $bit;
    $epsilonRate = ($epsilonRate ?? '') . (int) ! $bit;
}

var_dump(bindec($gammaRate) * bindec($epsilonRate));
