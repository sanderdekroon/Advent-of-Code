<?php

namespace AOC\Bingo;

class GridNumber
{
    protected int $number;
    protected bool $marked = false;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function mark(): GridNumber
    {
        $this->marked = true;

        return $this;
    }

    public function isMarked(): bool
    {
        return $this->marked;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
