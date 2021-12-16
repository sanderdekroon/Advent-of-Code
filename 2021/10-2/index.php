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
    protected array $repairedCharacters = [];

    public function __construct(array $line)
    {
        $this->line = $line;
    }

    public function repair(): array
    {
        foreach (array_reverse($this->stack) as $character) {
            $this->repairedCharacters[] = $this->delimiters[$character];
        }

        return $this->repairedCharacters;
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

$scores = [];
$points = [')' => 1, ']' => 2, '}' => 3, '>' => 4];

foreach ($lines as $i => $line) {
    $lineChecker = new LineChecker($line);

    try {
        $lineChecker->validate();
    } catch (UnexpectedCharacterException $e) {
        continue; // Disregard corrupted lines
    }

    $repaired = $lineChecker->repair();

    $scores[] = array_reduce($repaired, function ($carry, $item) use ($points) {
        return ($carry * 5) + $points[$item];
    }, 0);
}

sort($scores);

var_dump(array_slice($scores, floor(count($scores) / 2), 1));
