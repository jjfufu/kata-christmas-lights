<?php

namespace Tests;

use Kata\ChristmasLights;
use Kata\Coordinates;
use Kata\InvalidLightActionException;
use Kata\InvalidLightStatusException;
use Kata\OuterRangeException;
use PHPUnit\Framework\TestCase;

class ChristmasLightsTest extends TestCase
{
    public function testGetRowsNumberReturnCorrectNumber(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertEquals(10, $cl->getRowsNumber());
    }

    public function testGetColumnsNumberReturnCorrectNumber(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertEquals(10, $cl->getColumnsNumber());
    }

    public function testGetGridReturnArray(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertIsArray($cl->getGrid());
    }

    public function testGridMustHaveCorrectRowsCount(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertCount(10, $cl->getGrid());
    }

    public function testGridMustHaveCorrectColumnsCount(): void
    {
        $cl = new ChristmasLights(10, 10);
        foreach($cl->getGrid() as $row) {
            $this->assertCount(10, $row);
        }
    }

    public function testAllLightsAreOff(): void
    {
        $cl = new ChristmasLights(10, 10);
        foreach ($cl->getGrid() as $row) {
            foreach ($row as $light) {
                $this->assertEquals(ChristmasLights::LIGHT_STATUS_OFF, $light);
            }
        }
    }

    public function testSetLightStatus(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->setLightStatus(ChristmasLights::LIGHT_STATUS_ON, new Coordinates(1, 5));
        $this->assertEquals(ChristmasLights::LIGHT_STATUS_ON, $cl->getGrid()[1][5]);
    }

    public function testSetLightStatusThrowInvalidLightStatusException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new InvalidLightStatusException('test status is invalid', 0));
        $cl->setLightStatus('test', new Coordinates(1, 5));
    }

    public function testGetLightStatus(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertEquals(ChristmasLights::LIGHT_STATUS_OFF, $cl->getLightStatus(new Coordinates(1, 5)));
    }

    public function testGetLightStatusThrowOuterRangeException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new OuterRangeException());
        $cl->getLightStatus(new Coordinates(100, 9));
    }

    public function testCountLightsFromStatus(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertEquals(100, $cl->countStatus(ChristmasLights::LIGHT_STATUS_OFF));
    }

    public function testCountLightsFromStatusThrowInvalidLightStatusException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new InvalidLightStatusException('fake status is invalid', 0));
        $cl->countStatus('fake');
    }

    public function testIlluminateThrowInvalidLightActionException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new InvalidLightActionException('turnOn action is invalid', 0));
        $cl->illuminate('turnOn', new Coordinates(0, 10), new Coordinates(1, 10));
    }

    public function testTurnOnReturnValidCount(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertEquals(100, $cl->illuminate(ChristmasLights::LIGHT_STATUS_ON, new Coordinates(0, 0), new Coordinates(9, 9)));
    }

    public function testExercise(): void
    {
        $cl = new ChristmasLights(1000, 1000);
        //$this->assertEquals(1000000, $cl->illuminate(ChristmasLights::LIGHT_STATUS_ON, new Coordinates(0, 0), new Coordinates(999, 999)));

        $tests = [
            // turn on 887,9 through 959,629
            [
                'from' => new Coordinates(887, 9),
                'to' => new Coordinates(959, 629),
                'action' => ChristmasLights::LIGHT_STATUS_ON
            ],
            // turn on 454,398 through 844,448
            [
                'from' => new Coordinates(454, 398),
                'to' => new Coordinates(844, 448),
                'action' => ChristmasLights::LIGHT_STATUS_ON,
            ],
            // turn off 539,243 through 559,965
            [
                'from' => new Coordinates(539, 243),
                'to' => new Coordinates(559, 965),
                'action' => ChristmasLights::LIGHT_STATUS_OFF,
            ],
            // turn off 370,819 through 676,868
            [
                'from' => new Coordinates(370, 819),
                'to' => new Coordinates(676, 868),
                'action' => ChristmasLights::LIGHT_STATUS_OFF,
            ],
            // turn off 145,40 through 370,997
            [
                'from' => new Coordinates(145, 40),
                'to' => new Coordinates(370, 997),
                'action' => ChristmasLights::LIGHT_STATUS_OFF,
            ],
            // turn off 301,3 through 808,453
            [
                'from' => new Coordinates(301, 3),
                'to' => new Coordinates(808, 453),
                'action' => ChristmasLights::LIGHT_STATUS_OFF,
            ],
            // turn on 351,678 through 951,908
            [
                'from' => new Coordinates(351, 678),
                'to' => new Coordinates(951, 908),
                'action' => ChristmasLights::LIGHT_STATUS_ON,
            ],
            // toggle 720,196 through 897,994
            [
                'from' => new Coordinates(720, 196),
                'to' => new Coordinates(897, 994),
                'action' => ChristmasLights::LIGHT_TOGGLE,
            ],
            // toggle 831,394 through 904,860
            [
                'from' => new Coordinates(831, 394),
                'to' => new Coordinates(904, 860),
                'action' => ChristmasLights::LIGHT_TOGGLE,
            ],
        ];

        foreach($tests as $test) {
            $countX = max($test['from']->getX(), $test['to']->getX()) - min($test['from']->getX(), $test['to']->getX());
            $countY = max($test['from']->getY(), $test['to']->getY()) - min($test['from']->getY(), $test['to']->getY());
            $this->assertEquals((($countX * $countY) + ($countX + $countY)) + 1, $cl->illuminate($test['action'], $test['from'], $test['to']));
        }
    }
}