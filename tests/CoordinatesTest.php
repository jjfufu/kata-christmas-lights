<?php

namespace Tests;

use Kata\Coordinates;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    public function testGetXReturnValidInteger(): void
    {
        $c = new Coordinates(10, 10);
        $this->assertEquals(10, $c->getX());
    }

    public function testGetYReturnValidInteger(): void
    {
        $c = new Coordinates(10, 10);
        $this->assertEquals(10, $c->getY());
    }
}