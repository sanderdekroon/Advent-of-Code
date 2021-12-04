<?php

namespace AOC\Bingo;

require __DIR__ . '/vendor/autoload.php';

$lines = preg_split('/(\n\s*\n)+/', file_get_contents(__DIR__ . '/input.txt'));

$drawnNumbers = explode(',', $lines[0]);
unset($lines[0]);

$grids = array_map(fn($line) => new BingoGrid($line), $lines);

foreach ($drawnNumbers as $number) {
    foreach ($grids as $grid) {
        try {
            $grid->mark($number);
        } catch (BingoFoundException $e) {
            if (count($grids) <= 1) {
                printf('Solution: %s', reset($grids)->sumUnmarked() * $number);
                exit();
            }
            $bingoIdentifier = $e->getGrid()->getIdentifier();

            $grids = array_filter(
                $grids,
                fn($item) => $item->getIdentifier() !== $grid->getIdentifier()
            );
        }
    }
}


exit('No bingo :(');
