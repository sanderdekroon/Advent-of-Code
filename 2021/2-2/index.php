<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

$x = $y = $aim = 0;

foreach ($lines as $line) {
    [$command, $parameter] = explode(' ', $line);

    switch ($command) {
        case 'forward':
            $x += $parameter;
            $y += ($aim * $parameter);
            break;
        case 'up':
            $aim -= $parameter;
            break;
        case 'down':
            $aim += $parameter;
            break;
    }
}

var_dump($x * $y);
