<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

$lines = array_reduce($lines, function ($carry, $item) {
    [$from, $to] = explode(' -> ', $item);

    [$startX, $startY, $endX, $endY] = [$startX, $startY, $endX, $endY] = array_merge(
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
        if ($this->isDiagonal()) {
            return $this->calculateDiagonalCoordinates();
        }

        if ($this->isVertical()) {
            return $this->calculateCoordinates($this->startX, $this->startY, $this->endY, true);
        }

        if ($this->isHorizontal()) {
            return $this->calculateCoordinates($this->startY, $this->startX, $this->endX, false);
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

    public function isDiagonal(): bool
    {
        // Even diagonal e.g. 1,1 -> 3,3
        return ($this->startX === $this->startY  && $this->endX === $this->endY)

            // Uneven diagonal e.g. 8,0 -> 0,8
            || ($this->startX !== $this->endX && $this->startY !== $this->endY);
    }

    protected function calculateDiagonalCoordinates()
    {
        $startX = $this->startX;
        $startY = $this->startY;

        $list = [sprintf('%1$d_%2$d', $startX, $startY)];

        while ($startX !== $this->endX) {
            $list[] = sprintf(
                '%1$d_%2$d',
                ($startX < $this->endX ? ++$startX : --$startX),
                ($startY < $this->endY ? ++$startY : --$startY)
            );
        }

        return $list;
    }

    protected function calculateCoordinates(int $base, int $start, int $end, $vertical = true)
    {
        $list = [sprintf(
            $vertical ? '%1$d_%2$d' : '%2$d_%1$d',
            $base,
            $start
        )];

        while ($start !== $end) {
            $list[] = sprintf(
                $vertical ? '%1$d_%2$d' : '%2$d_%1$d',
                $base,
                ($start < $end ? ++$start : --$start)
            );
        }

        return $list;
    }
}

echo count(array_filter($lines, function ($item) {
    return $item >= 2;
})); //20299
