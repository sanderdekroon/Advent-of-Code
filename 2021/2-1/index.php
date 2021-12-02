<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

$x = $y = 0;

foreach ($lines as $line) {
    [$command, $parameter] = explode(' ', $line);

    switch ($command) {
        case 'forward':
            $x += $parameter;
            break;
        case 'up':
            $y -= $parameter;
            break;
        case 'down':
            $y += $parameter;
            break;
    }
}

var_dump($x * $y);
