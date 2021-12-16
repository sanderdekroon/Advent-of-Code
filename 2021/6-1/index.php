<?php

$lines = array_filter(explode(',', file_get_contents(__DIR__ . '/input.txt')));

$fishes = array_map(fn($item) => new Fish($item), array_map('intval', $lines));

class Fish
{
    protected int $timer;

    public function __construct(int $timer)
    {
        $this->timer = $timer;
    }

    public function grow(): Fish
    {
        $this->timer--;

        return $this;
    }

    public function canReproduce(): bool
    {
        return $this->timer === 0;
    }

    public function reproduce(): Fish
    {
        $this->resetTimer();

        return new self(8);
    }

    protected function resetTimer(): Fish
    {
        $this->timer = 6;

        return $this;
    }
}

for ($i = 0; $i < 80; $i++) {
    foreach ($fishes as $fish) {
        if ($fish->canReproduce()) {
            $fishes[] = $fish->reproduce();
        } else {
            $fish->grow();
        }
    }
}

echo count($fishes);
