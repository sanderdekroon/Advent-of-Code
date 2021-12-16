<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));

$grid = [];
foreach ($lines as $row => $line) {
    $line = str_split($line);
    $lines[$row] = $line;
    foreach ($line as $column => $energy) {
        $grid[$row][$column] = new Octopus((int) $energy, $row, $column);
    }
}

class Octopus
{
    protected int $energy;
    protected int $row;
    protected int $column;
    protected bool $hasFlashed = false;
    protected int $flashCount = 0;

    public function __construct(?int $energy, int $row, int $column)
    {
        $this->energy = $energy;
        $this->row = $row;
        $this->column = $column;
    }

    public function flash(): Octopus
    {
        $this->energy = 0;
        $this->flashCount++;
        $this->setHasFlashed();

        return $this;
    }

    public function hasFlashed(): bool
    {
        return $this->hasFlashed;
    }

    public function getFlashCount(): int
    {
        return $this->flashCount;
    }

    public function setHasFlashed(bool $flashed = true): Octopus
    {
        $this->hasFlashed = $flashed;

        return $this;
    }

    public function increaseEnergy()
    {
        if (! $this->hasFlashed()) {
            $this->energy++;
        }

        return $this;
    }

    public function getEnergy(): ?int
    {
        return $this->energy;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getRowAndColumn(): string
    {
        return $this->getRow() . '_' . $this->getColumn();
    }
}

class Grid
{
    protected array $grid;

    public function __construct(array $grid)
    {
        $this->grid = $grid;
    }

    public function lines(): array
    {
        return $this->grid;
    }

    public function resetFlash()
    {
        foreach ($this->lines() as $line) {
            foreach ($line as $octopus) {
                $octopus->setHasFlashed(false);
            }
        }
    }

    public function getConnected(Octopus $octopus): array
    {
        return array_filter([
            // top
            $this->fromGrid($octopus->getRow() + 1, $octopus->getColumn()),
            // top right
            $this->fromGrid($octopus->getRow() + 1, $octopus->getColumn() + 1),
            // right
            $this->fromGrid($octopus->getRow(), $octopus->getColumn() + 1),
            // bottom right
            $this->fromGrid($octopus->getRow() - 1, $octopus->getColumn() + 1),
            // bottom
            $this->fromGrid($octopus->getRow() - 1, $octopus->getColumn()),
            // bottom left
            $this->fromGrid($octopus->getRow() - 1, $octopus->getColumn() - 1),
            // left
            $this->fromGrid($octopus->getRow(), $octopus->getColumn() - 1),
            // top left
            $this->fromGrid($octopus->getRow() + 1, $octopus->getColumn() - 1),
        ]);
    }

    protected function fromGrid(int $row, int $column): ?Octopus
    {
        return $this->grid[$row][$column] ?? null;
    }
}

$grid = new Grid($grid);

function handleOctopus($octopus, $grid)
{
    if ($octopus->hasFlashed() === false && $octopus->getEnergy() === 9) {
        $octopus->flash();

        $connected = $grid->getConnected($octopus);

        foreach ($connected as $connectedOctopus) {
            handleOctopus($connectedOctopus, $grid);
        }
    } else {
        $octopus->increaseEnergy();
    }
}

for ($i = 0; $i < 100; $i++) {
    $grid->resetFlash();
    foreach ($grid->lines() as $row => $line) {
        foreach ($line as $column => $octopus) {
            handleOctopus($octopus, $grid);
        }
    }
}

$list = array_map(function ($line) {
    return array_sum(array_map(function ($octopus) {
        return $octopus->getFlashCount();
    }, $line));
}, $grid->lines());

var_dump(array_sum($list));
