<?php

namespace AOC\Bingo;

class GridLine
{
    protected array $line;
    protected bool $marked = false;

    public function __construct(array $line)
    {
        $this->line = $line;
    }

    public function mark(int $number)
    {
        array_walk($this->line, function ($item) use ($number) {
            if ($item->getNumber() === $number) {
                $item->mark();
            }
        });

        return $this;
    }

    public function sumUnmarked(): int
    {
        return array_sum(array_map(
            fn($item) => $item->getNumber(),
            array_filter($this->line, fn($item) => ! $item->isMarked())
        ));
    }

    public function hasBingo(): bool
    {
        $filtered = array_filter(
            $this->line,
            fn($item) => $item->isMarked()
        );

        return count($filtered) === count($this->line);
    }

    public function toArray(): array
    {
        return $this->line;
    }

    public function count(): int
    {
        return count($this->line);
    }
}
