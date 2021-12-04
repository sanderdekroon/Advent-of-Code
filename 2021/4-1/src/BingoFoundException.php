<?php

namespace AOC\Bingo;

use Exception;

class BingoFoundException extends Exception
{
    protected ?BingoGrid $grid = null;
    protected ?GridLine $gridLine = null;

    public static function fromGridAndLine(BingoGrid $grid, GridLine $line)
    {
        return (new self("Bingo found!"))->setGrid($grid)->gridLine($line);
    }

    public function getGrid(): ?BingoGrid
    {
        return $this->grid;
    }

    public function getGridLine(): ?GridLine
    {
        return $this->gridLine;
    }

    public function gridLine(GridLine $line)
    {
        $this->gridLine = $line;

        return $this;
    }

    public function setGrid(BingoGrid $grid)
    {
        $this->grid = $grid;

        return $this;
    }
}
