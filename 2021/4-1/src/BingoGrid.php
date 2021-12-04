<?php

namespace AOC\Bingo;

class BingoGrid
{
    protected array $grid;

    public function __construct(string $grid)
    {
        $this->grid = $this->parseStringGrid($grid);
    }

    public function sumUnmarked(): int
    {
        return array_sum(array_map(
            fn($line) => $line->sumUnmarked(),
            $this->grid
        ));
    }

    public function mark(int $number): BingoGrid
    {
        foreach ($this->grid as $line) {
            $line->mark($number);
        }

        $this->checkForBingo();

        return $this;
    }

    public function checkForBingo(): bool
    {
        $lines = array_merge(
            $this->getHorzontalLines(),
            $this->getVerticalLines()
        );

        foreach ($lines as $line) {
            if ($line->hasBingo()) {
                throw BingoFoundException::fromGridAndLine($this, $line);
            }
        }

        return false;
    }

    public function parseStringGrid(string $grid): array
    {
        return array_map(
            [$this, 'parsedGridLine'],
            explode("\n", $grid)
        );
    }

    public function parsedGridLine(string $line): GridLine
    {
        $line = explode(' ', ltrim(str_replace('  ', ' ', $line)));
        $line = array_map(fn($item) => new GridNumber((int) $item), $line);

        return new GridLine($line);
    }

    public function getHorzontalLines(): array
    {
        return $this->grid;
    }

    protected function getVerticalLines(): array
    {
        $colCount = reset($this->grid)->count();

        $vertical = [];
        for ($i = 0; $i < $colCount; $i++) {
            $vertical[] = new GridLine(
                array_column(
                    array_map(fn($line) => $line->toArray(), $this->grid),
                    $i
                )
            );
        }

        return $vertical;
    }
}
