<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));
$lines = array_map(fn($line) => str_split($line), $lines);

class Point
{
    protected ?string $depth;
    protected int $rowNo;
    protected int $columNo;

    public function __construct(?string $depth, int $rowNo, int $columnNo)
    {
        $this->depth = $depth;
        $this->rowNo = $rowNo;
        $this->columNo = $columnNo;
    }

    public function getDepth(): ?string
    {
        return $this->depth;
    }

    public function getRow(): int
    {
        return $this->rowNo;
    }

    public function getColumn(): int
    {
        return $this->columNo;
    }

    public function getRowAndColumn(): string
    {
        return $this->getRow() . '_' . $this->getColumn();
    }
}

class Basin
{
    protected array $pool;
    protected $visited = [];
    protected $connected = [];

    public function __construct(array $pool)
    {
        $this->pool = $pool;
    }

    public function fill(Point $point): void
    {
        if ($this->hasVisited($point) || $point->getDepth() === null || $point->getDepth() == 9) {
            return;
        }

        $this->addConnected($point)->addVisited($point);

        $this->fill($this->fromPool($point->getRow(), $point->getColumn() + 1));
        $this->fill($this->fromPool($point->getRow(), $point->getColumn() - 1));
        $this->fill($this->fromPool($point->getRow() + 1, $point->getColumn()));
        $this->fill($this->fromPool($point->getRow() - 1, $point->getColumn()));
    }

    public function size(): int
    {
        return count($this->connected);
    }

    public function getConnected(): array
    {
        return $this->connected;
    }

    protected function fromPool(int $row, int $column): Point
    {
        $depth = $this->pool[$row][$column] ?? null;

        return new Point($depth, $row, $column);
    }

    protected function addVisited(Point $point): Basin
    {
        $this->visited[$point->getRowAndColumn()] = true;

        return $this;
    }

    protected function hasVisited(Point $point): bool
    {
        return isset($this->visited[$point->getRowAndColumn()]);
    }

    protected function addConnected(Point $point): Basin
    {
        $this->connected[] = $point;

        return $this;
    }
}

$lowestNumbers = $points = [];
foreach ($lines as $rowNo => $line) {
    foreach ($line as $columnNo => $number) {
        $right = $line[$columnNo + 1] ?? null;

        $left = $line[$columnNo - 1] ?? null;

        $under = $lines[$rowNo + 1][$columnNo] ?? null;

        $over = $lines[$rowNo - 1][$columnNo] ?? null;

        if (
            ($right === null || $number < $right) &&
            ($left === null || $number < $left) &&
            ($under === null || $number < $under) &&
            ($over === null || $number < $over)
        ) {
            $point = new Point($number, $rowNo, $columnNo);
            $basin = new Basin($lines);
            $basin->fill($point);

            $basins[] = $basin;
        }
    }
}

usort($basins, fn($itemA, $itemB) => $itemA->size() <= $itemB->size());

$basins = array_slice($basins, 0, 3);

var_dump(array_product(array_map(fn($item) => $item->size(), $basins)));
