<?php

namespace Kata;

class ChristmasLights
{
    public const BRIGHTNESS_INCREASE = 'INCREASE';
    public const BRIGHTNESS_DECREASE = 'DECREASE';
    public const BRIGHTNESS_INCREASE_MORE = 'INCREASE_MORE';
    public const BRIGHTNESS_ACTIONS = [
        self::BRIGHTNESS_INCREASE,
        self::BRIGHTNESS_DECREASE,
        self::BRIGHTNESS_INCREASE_MORE
    ];

    public const BRIGHTNESS_START = 0;

    private array $grid = [];

    public function __construct(
        private readonly int $rowsNumber,
        private readonly int $columnsNumber
    )
    {
        $this->buildGrid();
    }

    private function buildGrid(): void
    {
        for ($i = 0; $i < $this->rowsNumber; $i++) {
            $row = [];
            for ($j = 0; $j < $this->columnsNumber; $j++) {
                $row[] = self::BRIGHTNESS_START;
            }
            $this->grid[] = $row;
        }
    }

    public function getRowsNumber(): int
    {
        return $this->rowsNumber;
    }

    public function getColumnsNumber(): int
    {
        return $this->columnsNumber;
    }

    public function getGrid(): array
    {
        return $this->grid;
    }

    public function setLightBrightness(string $action, Coordinates $coordinates): void
    {
        $this->validateLightAction($action);

        $brightness = $this->getLightBrightness($coordinates);
        if ($brightness === 0 && $action === self::BRIGHTNESS_DECREASE) {
            return;
        }

        $this->grid[$coordinates->getX()][$coordinates->getY()] = match ($action) {
            self::BRIGHTNESS_INCREASE => ++$brightness,
            self::BRIGHTNESS_DECREASE => --$brightness,
            self::BRIGHTNESS_INCREASE_MORE => $brightness + 2
        };
    }

    public function getLightBrightness(Coordinates $coordinates): int
    {
        if ($coordinates->getX() > $this->getRowsNumber() || $coordinates->getY() > $this->getColumnsNumber()) {
            throw new OuterRangeException();
        }

        return $this->grid[$coordinates->getX()][$coordinates->getY()];
    }

    public function getGridBrightness(): int
    {
        $totalBrightness = 0;
        foreach ($this->getGrid() as $row) {
            foreach ($row as $brightness) {
                $totalBrightness += $brightness;
            }
        }

        return $totalBrightness;
    }

    public function illuminate(string $action, Coordinates $from, Coordinates $to): void
    {
        $this->validateLightAction($action);
        for ($rowIndex = $from->getX(); $rowIndex <= $to->getX(); $rowIndex++) {
            for ($columnIndex = $from->getY(); $columnIndex <= $to->getY(); $columnIndex++) {
                $this->setLightBrightness($action, new Coordinates($rowIndex, $columnIndex));
            }
        }
    }

    private function validateLightAction(string $action): void
    {
        if (!in_array($action, self::BRIGHTNESS_ACTIONS, true)) {
            throw new InvalidLightActionException(sprintf('%s action is invalid', $action));
        }
    }
}