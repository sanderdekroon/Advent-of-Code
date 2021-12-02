<?php

$file = file_get_contents(__DIR__ . '/two_input.txt');
$lines = array_filter(explode("\n", $file));

$valid = 0;

foreach ($lines as $line) {
    [$range, $character, $password] = analyzePasswordLine($line);

    $characterCount = substr_count($password, $character);

    if ($characterCount >= $range[0] && $characterCount <= $range[1]) {
        printf(
            'Valid password: %s (expected %d-%d of %s, found %d) <br/>',
            $password,
            $range[0],
            $range[1],
            $character,
            $characterCount
        );

        $valid++;
    }
}

var_dump(compact('valid'));

function analyzePasswordLine(string $line)
{
    [$range, $character, $password] = explode(' ', $line);

    $range = explode('-', $range);

    sort($range);

    return [$range, substr($character, 0, 1), $password];
}
