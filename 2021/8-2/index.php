<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

function sortArrayValues(array $input)
{
    return array_map(function ($item) {
        $item = str_split($item);

        sort($item);

        return implode('', $item);
    }, $input);
}

function getBaseSignalmap(array $signalPatterns)
{
    $signalMap = [];

    foreach ($signalPatterns as $pattern) {
        switch (strlen($pattern)) {
            case 2:
                $signalMap[1] = $pattern;
                break;
            case 3:
                $signalMap[7] = $pattern;
                break;
            case 4:
                $signalMap[4] = $pattern;
                break;
            case 7:
                $signalMap[8] = $pattern;
                break;
        }
    }

    return $signalMap;
}

function assertDiffIs(string $itemA, string $itemB, int $diffCount)
{
    $diff = array_diff(str_split($itemA), str_split($itemB));

    return count($diff) === $diffCount;
}

$count = 0;

foreach ($lines as $line) {
    [$signalPatterns, $outputValues] = explode(' | ', $line);

    $signalPatterns = explode(' ', $signalPatterns);
    $outputValues = explode(' ', $outputValues);
    $signalMap = getBaseSignalmap($signalPatterns);


    // Map out 0, 6 and 9
    $zeroSixOrNine = array_filter($signalPatterns, fn($item) => strlen($item) === 6);

    $six = array_filter($zeroSixOrNine, function ($item) use ($signalMap) {
        return assertDiffIs($item, $signalMap[1], 5);
    });

    $nine = array_filter($zeroSixOrNine, function ($item) use ($six, $signalMap) {
        if ($item === reset($six)) {
            return false;
        }

        return assertDiffIs($item, $signalMap[4], 2);
    });

    $signalMap[6] = reset($six);
    $signalMap[9] = reset($nine);
    $signalMap[0] = @reset(array_filter($zeroSixOrNine, function ($item) use ($signalMap) {
        return $item !== $signalMap[6] && $item !== $signalMap[9];
    }));


    // Map out 2, 3 and 5
    $twoThreeOrFive = array_filter($signalPatterns, fn($item) => strlen($item) === 5);

    $three = array_filter($twoThreeOrFive, function ($item) use ($signalMap) {
        return assertDiffIs($item, $signalMap[1], 3);
    });

    $five = array_filter($twoThreeOrFive, function ($item) use ($three, $signalMap) {
        if ($item === reset($three)) {
            return false;
        }

        return assertDiffIs($item, $signalMap[6], 0);
    });

    $signalMap[3] = reset($three);
    $signalMap[5] = reset($five);
    $signalMap[2] = @reset(array_filter($twoThreeOrFive, function ($item) use ($signalMap) {
        return $item !== $signalMap[3] && $item !== $signalMap[5];
    }));

    $signalMap = array_flip(sortArrayValues($signalMap));
    $outputValues = sortArrayValues($outputValues);

    foreach ($outputValues as $i => $outputValue) {
        $outputValues[$i] = $signalMap[$outputValue];
    }

    $count += (implode('', $outputValues));
}

var_dump($count);
