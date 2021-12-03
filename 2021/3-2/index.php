<?php

$lines = array_map(function ($item) {
    return str_split($item);
}, array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt'))));

function getMostCommonBit(array $list, int $position): int
{
    $avg = array_sum(array_column($list, $position)) / count($list);

    return $avg === 0.5 ? 1 : (int) round($avg);
}

function filterByPositionedBit(array $list, int $bit, int $position): array
{
    return array_filter($list, function ($item) use ($bit, $position) {
        return (int) $item[$position] === $bit;
    });
}

$oxygenList = $scrubberList = $lines;

for ($i = 0; $i < 12; $i++) {
    if (count($oxygenList) > 1) {
        $oxygenList = filterByPositionedBit(
            $oxygenList,
            getMostCommonBit($oxygenList, $i),
            $i
        );
    }

    if (count($scrubberList) > 1) {
        $scrubberList = filterByPositionedBit(
            $scrubberList,
            (int) ! getMostCommonBit($scrubberList, $i),
            $i
        );
    }
}

var_dump(bindec(implode('', reset($oxygenList))) * bindec(implode('', reset($scrubberList))));
