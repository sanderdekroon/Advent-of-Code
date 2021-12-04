<?php

namespace AOC\Bingo;

require __DIR__ . '/vendor/autoload.php';

$lines = preg_split('/(\n\s*\n)+/', file_get_contents(__DIR__ . '/input.txt'));

$drawnNumbers = explode(',', $lines[0]);
unset($lines[0]);

$grids = array_map(fn($line) => new BingoGrid($line), $lines);

try {
    foreach ($drawnNumbers as $number) {
        array_walk($grids, fn($grid) => $grid->mark($number));
    }
} catch (BingoFoundException $e) {
    var_dump($e->getGrid()->sumUnmarked() * $number);
    exit();
}

exit('No bingo :(');
