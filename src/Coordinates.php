<?php

namespace Kata;

class Coordinates {
    public function __construct(
        private readonly int $x,
        private readonly int $y
    )
    {
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}