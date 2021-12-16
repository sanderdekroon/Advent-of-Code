<?php

$lines = array_filter(explode("\n", file_get_contents(__DIR__ . '/input.txt')));
$lines = array_map(fn($line) => str_split($line), $lines);

class UnexpectedCharacterException extends Exception
{
    protected string $character;

    public function setCharacter(string $character): UnexpectedCharacterException
    {
        $this->character = $character;

        return $this;
    }

    public function getCharacter(): string
    {
        return $this->character;
    }
}

class LineChecker
{
    protected array $line;
    protected array $stack = [];
    protected int $depth = 0;

    protected array $delimiters = [
        '(' => ')', '[' => ']', '{' => '}', '<' => '>',
    ];

    protected array $closingCharacters = [')', ']', '}', '>'];

    public function __construct(array $line)
    {
        $this->line = $line;
    }

    public function validate()
    {
        foreach ($this->line as $character) {
            $this->validateCharacter($character);
        }

        return true;
    }

    protected function validateCharacter(string $character): LineChecker
    {
        if ($this->isClosingCharacter($character)) {
            if ($this->characterClosesCorrectly($character, $this->depth)) {
                $this->popStackEntry($this->depth);

                $this->depth--;

                return $this;
            }

            $error = new UnexpectedCharacterException("Unexpected character {$character}");
            $error->setCharacter($character);

            throw $error;
        }

        if ($this->isFirstOpeningCharacter($this->depth)) {
            return $this->pushStackEntry($character, $this->depth);
        }

        $this->depth++;

        return $this->pushStackEntry($character, $this->depth);
    }

    protected function characterClosesCorrectly(string $character, int $depth): bool
    {
        $prevCharacter = $this->stack[$depth];

        return ($this->delimiters[$prevCharacter] ?? null) === $character;
    }

    protected function pushStackEntry(string $character, int $depth): LineChecker
    {
        $this->stack[$depth] = $character;

        return $this;
    }

    protected function popStackEntry(int $depth): LineChecker
    {
        unset($this->stack[$depth]);

        return $this;
    }

    protected function isFirstOpeningCharacter(int $depth): bool
    {
        return ! isset($this->stack[$depth]);
    }

    protected function isClosingCharacter($character): bool
    {
        return in_array($character, $this->closingCharacters);
    }
}

$invalid = [];

foreach ($lines as $i => $line) {
    try {
        $lineChecker = new LineChecker($line);
        $lineChecker->validate();
    } catch (UnexpectedCharacterException $e) {
        $invalid[] = $e->getCharacter();
    }
}


$points = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];

var_dump(array_sum(array_map(fn($item) => $points[$item], $invalid)));
