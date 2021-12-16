<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

$count = 0;

foreach ($lines as $line) {
    [$signalPatterns, $outputValue] = explode('|', $line);

    $values = explode(' ', $outputValue);

    foreach ($values as $value) {
        switch (strlen($value)) {
            case 2:
            case 3:
            case 4:
            case 7:
                $count++;
                break;
        }
    }
}


var_dump($count);
