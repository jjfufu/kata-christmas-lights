<?php

namespace Tests;

use Kata\ChristmasLights;
use Kata\Coordinates;
use Kata\InvalidLightActionException;
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
        foreach ($cl->getGrid() as $row) {
            $this->assertCount(10, $row);
        }
    }

    public function testAllLightsHaveZeroBrightness(): void
    {
        $cl = new ChristmasLights(10, 10);
        foreach ($cl->getGrid() as $row) {
            foreach ($row as $light) {
                $this->assertEquals(ChristmasLights::BRIGHTNESS_START, $light);
            }
        }
    }

    public function testSetLightBrightness(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->setLightBrightness(ChristmasLights::BRIGHTNESS_INCREASE, new Coordinates(1, 5));
        $this->assertEquals(1, $cl->getGrid()[1][5]);
    }

    public function testDecreaseZeroLightBrightnessMustNotChange(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->setLightBrightness(ChristmasLights::BRIGHTNESS_DECREASE, new Coordinates(1, 5));
        $this->assertEquals(0, $cl->getGrid()[1][5]);
    }

    public function testDecreaseLightBrightnessMustBeZero(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->setLightBrightness(ChristmasLights::BRIGHTNESS_INCREASE, new Coordinates(1, 5));
        $cl->setLightBrightness(ChristmasLights::BRIGHTNESS_DECREASE, new Coordinates(1, 5));
        $this->assertEquals(0, $cl->getGrid()[1][5]);
    }

    public function testIncreaseMoreLightBrightnessMustBeTwo(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->setLightBrightness(ChristmasLights::BRIGHTNESS_INCREASE_MORE, new Coordinates(1, 5));
        $this->assertEquals(2, $cl->getGrid()[1][5]);
    }

    public function testSetLightBrightnessThrowInvalidLightActionException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new InvalidLightActionException('test action is invalid', 0));
        $cl->setLightBrightness('test', new Coordinates(1, 5));
    }

    public function testGetLightBrightnessMustBeZero(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->assertEquals(0, $cl->getLightBrightness(new Coordinates(1, 5)));
    }

    public function testGetLightBrightnessThrowOuterRangeException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new OuterRangeException());
        $cl->getLightBrightness(new Coordinates(100, 9));
    }

    public function testIlluminateThrowInvalidLightActionException(): void
    {
        $cl = new ChristmasLights(10, 10);
        $this->expectExceptionObject(new InvalidLightActionException('INCREAASE action is invalid', 0));
        $cl->illuminate('INCREAASE', new Coordinates(0, 10), new Coordinates(1, 10));
    }

    public function testGetGridBrightnessReturnValidCountAfterIncrease(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->illuminate(ChristmasLights::BRIGHTNESS_INCREASE, new Coordinates(0, 0), new Coordinates(9, 9));
        $this->assertEquals(100, $cl->getGridBrightness());
    }

    public function testGetGridBrightnessReturnValidCountAfterIncreaseMore(): void
    {
        $cl = new ChristmasLights(10, 10);
        $cl->illuminate(ChristmasLights::BRIGHTNESS_INCREASE_MORE, new Coordinates(0, 0), new Coordinates(9, 9));
        $this->assertEquals(200, $cl->getGridBrightness());
    }

    public function testKata(): void
    {
        $santaInstructions = [
            // turn on 887,9 through 959,629
            [
                'from' => new Coordinates(887, 9),
                'to' => new Coordinates(959, 629),
                'action' => ChristmasLights::BRIGHTNESS_INCREASE
            ],
            // turn on 454,398 through 844,448
            [
                'from' => new Coordinates(454, 398),
                'to' => new Coordinates(844, 448),
                'action' => ChristmasLights::BRIGHTNESS_INCREASE,
            ],
            // turn off 539,243 through 559,965
            [
                'from' => new Coordinates(539, 243),
                'to' => new Coordinates(559, 965),
                'action' => ChristmasLights::BRIGHTNESS_DECREASE,
            ],
            // turn off 370,819 through 676,868
            [
                'from' => new Coordinates(370, 819),
                'to' => new Coordinates(676, 868),
                'action' => ChristmasLights::BRIGHTNESS_DECREASE,
            ],
            // turn off 145,40 through 370,997
            [
                'from' => new Coordinates(145, 40),
                'to' => new Coordinates(370, 997),
                'action' => ChristmasLights::BRIGHTNESS_DECREASE,
            ],
            // turn off 301,3 through 808,453
            [
                'from' => new Coordinates(301, 3),
                'to' => new Coordinates(808, 453),
                'action' => ChristmasLights::BRIGHTNESS_DECREASE,
            ],
            // turn on 351,678 through 951,908
            [
                'from' => new Coordinates(351, 678),
                'to' => new Coordinates(951, 908),
                'action' => ChristmasLights::BRIGHTNESS_INCREASE,
            ],
            // toggle 720,196 through 897,994
            [
                'from' => new Coordinates(720, 196),
                'to' => new Coordinates(897, 994),
                'action' => ChristmasLights::BRIGHTNESS_INCREASE_MORE,
            ],
            // toggle 831,394 through 904,860
            [
                'from' => new Coordinates(831, 394),
                'to' => new Coordinates(904, 860),
                'action' => ChristmasLights::BRIGHTNESS_INCREASE_MORE,
            ],
        ];
        $cl = new ChristmasLights(1000, 1000);
        foreach ($santaInstructions as $instruction) {
            $cl->illuminate($instruction['action'], $instruction['from'], $instruction['to']);
        }

        echo 'What is the total brightness of all lights combined after following Santa’s instructions ?' . PHP_EOL;
        echo sprintf('The total brightness is : %s', $cl->getGridBrightness());

        $this->assertTrue(true);
    }
}