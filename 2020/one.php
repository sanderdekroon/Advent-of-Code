<?php

$file = file_get_contents(__DIR__ . '/one_input.txt');
$parts = explode("\n", $file);

foreach ($parts as $partOne) {
    foreach ($parts as $partTwo) {
        foreach ($parts as $partThree) {
            if (((int) $partOne + (int) $partTwo + (int) $partThree) === 2020) {
                echo printf('%s + %s + %s = 2020', $partOne, $partTwo, $partThree);
                exit();
            }
        }
    }
}
