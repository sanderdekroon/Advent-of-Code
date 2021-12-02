<?php

$file = file_get_contents(__DIR__ . '/two_input.txt');
$lines = array_filter(explode("\n", $file));

$valid = 0;

foreach ($lines as $line) {
    [$positions, $character, $password] = analyzePasswordLine($line);
    [$posOne, $posTwo] = $positions;
    $password = str_split($password);

    if (($password[$posOne - 1] === $character && $password[$posTwo - 1] === $character)) {
        continue;
    }

    if ($password[$posOne - 1] === $character || $password[$posTwo - 1] === $character) {
        printf(
            'Valid password: %s (expected %s at %d or %d) <br/>',
            implode('', $password),
            $character,
            $posOne,
            $posTwo
        );

        $valid++;
    }
}

var_dump(compact('valid'));

function analyzePasswordLine(string $line)
{
    [$positions, $character, $password] = explode(' ', $line);

    $positions = explode('-', $positions);

    return [$positions, substr($character, 0, 1), $password];
}
