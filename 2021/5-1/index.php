<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

$lines = array_reduce($lines, function ($carry, $item) {
    [$from, $to] = explode(' -> ', $item);

    [$startX, $startY, $endX, $endY] = array_merge(
        array_map('intval', explode(',', $from)),
        array_map('intval', explode(',', $to))
    );

    $vent = new Vent($startX, $startY, $endX, $endY);

    foreach ($vent->getCooridinates() as $coordinate) {
        $carry[$coordinate] = isset($carry[$coordinate]) ? $carry[$coordinate] + 1 : 1;
    }

    return $carry;
}, []);

class Vent
{
    protected int $startX;
    protected int $startY;
    protected int $endX;
    protected int $endY;

    public function __construct(int $startX, int $startY, int $endX, int $endY)
    {
        $this->startX = $startX;
        $this->startY = $startY;
        $this->endX = $endX;
        $this->endY = $endY;
    }

    public function getCooridinates(): array
    {
        if ($this->isVertical() && $this->isHorizontal()) {
            return [];
        }

        if ($this->isVertical()) { // Calculate using Y $startX === $endX
            return $this->calculateVerticalCoordinates($this->startX, $this->startY, $this->endY);
        }

        if ($this->isHorizontal()) { // Calculate using Y $startX === $endX
            return $this->calculateHorizontalCoordinates($this->startY, $this->startX, $this->endX);
        }

        return [];
    }

    public function isHorizontal(): bool
    {
        return $this->startX !== $this->endX;
    }

    public function isVertical(): bool
    {
        return $this->startY !== $this->endY;
    }

    protected function calculateVerticalCoordinates(int $base, int $start, int $end)
    {
        $list = [sprintf('%d_%d', $base, $start)];

        while ($start !== $end) {
            $list[] = sprintf('%d_%d', $base, ($start < $end ? ++$start : --$start));
        }

        return $list;
    }

    protected function calculateHorizontalCoordinates(int $base, int $start, int $end)
    {
        $list = [sprintf('%d_%d', $start, $base)];

        while ($start !== $end) {
            $list[] = sprintf('%d_%d', ($start < $end ? ++$start : --$start), $base);
        }

        return $list;
    }
}

echo count(array_filter($lines, function ($item) {
    return $item >= 2;
})); //5438
