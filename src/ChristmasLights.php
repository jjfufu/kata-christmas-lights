<?php

namespace Kata;

class ChristmasLights
{
    public const LIGHT_STATUS_ON = 'ON';
    public const LIGHT_STATUS_OFF = 'OFF';
    public const LIGHT_STATUSES = [
        self::LIGHT_STATUS_ON,
        self::LIGHT_STATUS_OFF,
    ];

    public const LIGHT_TOGGLE = 'TOGGLE';
    public const LIGHT_ACTIONS = [
        self::LIGHT_STATUS_ON,
        self::LIGHT_STATUS_OFF,
        self::LIGHT_TOGGLE
    ];

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
                $row[] = self::LIGHT_STATUS_OFF;
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

    public function setLightStatus(string $status, Coordinates $coordinates): void
    {
        $this->validateLightStatus($status);

        $this->grid[$coordinates->getX()][$coordinates->getY()] = $status;
    }

    public function getLightStatus(Coordinates $coordinates): string
    {
        if ($coordinates->getX() > $this->getRowsNumber() || $coordinates->getY() > $this->getColumnsNumber()) {
            throw new OuterRangeException();
        }

        return $this->grid[$coordinates->getX()][$coordinates->getY()];
    }

    public function countStatus(string $status): int
    {
        $this->validateLightStatus($status);

        $counter = 0;
        foreach ($this->getGrid() as $row) {
            foreach ($row as $state) {
                if ($state === $status) {
                    $counter++;
                }
            }
        }

        return $counter;
    }

    public function illuminate(string $action, Coordinates $from, Coordinates $to): int
    {
        $this->validateLightAction($action);

        $stateChangedCounter = 0;
        for ($rowIndex = $from->getX(); $rowIndex <= $to->getX(); $rowIndex++) {
            for ($columnIndex = $from->getY(); $columnIndex <= $to->getY(); $columnIndex++) {
                $coordinates = new Coordinates($rowIndex, $columnIndex);

                $currentStatus = $this->getLightStatus($coordinates);
                $newStatus = $action;

                if ($action === self::LIGHT_TOGGLE) {
                    $newStatus = $currentStatus === self::LIGHT_STATUS_ON ? self::LIGHT_STATUS_OFF : self::LIGHT_STATUS_ON;
                }

                if ($currentStatus !== $newStatus) {
                    $this->setLightStatus($newStatus, $coordinates);
                }

                $stateChangedCounter++;
            }
        }

        return $stateChangedCounter;
    }

    private function validateLightStatus(string $status): void
    {
        if (!in_array($status, self::LIGHT_STATUSES, true)) {
            throw new InvalidLightStatusException(sprintf('%s status is invalid', $status));
        }
    }

    private function validateLightAction(string $action): void
    {
        if (!in_array($action, self::LIGHT_ACTIONS, true)) {
            throw new InvalidLightActionException(sprintf('%s action is invalid', $action));
        }
    }
}