<?php

namespace Battleships\Helper;

use Battleships\Game\Coordinate;
use Battleships\Game\Fleet;
use Battleships\Game\Ship;

class BattlefieldDeployer
{

    /**
     * @var Fleet
     */
    private $fleet;

    public function __construct(Fleet $fleet)
    {
        $this->fleet = $fleet;
    }

    public function deploy()
    {
        if (!$this->fleet->isDeployed()) {
            foreach ($this->fleet as $ship) {
                $this->anchorShip($ship);
            }
        }
    }

    private function anchorShip(Ship $ship)
    {
        while (!$ship->isAnchored()) {
            $direction = rand() % 2 ? BattlefieldCreator::DIRECTION_VERTICAL : BattlefieldCreator::DIRECTION_HORIZONTAL;

            $startColumn = rand(BattlefieldCreator::MIN_COLUMNS, BattlefieldCreator::MAX_COLUMNS);
            $startRow = rand(BattlefieldCreator::MIN_ROWS, BattlefieldCreator::MAX_ROWS);

            $startPoint = new Coordinate($startRow, $startColumn);

            $anchorPoints = $this->generateAnchorPoints($startPoint, $ship->getSize(), $direction);

            if (!$this->hasCollision($anchorPoints)) {
                $ship->setAnchorPoints($anchorPoints);
            }
        }
    }

    private function generateAnchorPoints(Coordinate $startPoint, $size, $direction)
    {
        $anchorPoints = array($startPoint);

        for ($i = 1; $i < $size; $i++) {
            if ($direction == BattlefieldCreator::DIRECTION_HORIZONTAL) {
                list($column, $row) = array($startPoint->getColumn(), $startPoint->getRow() + $i);
            }
            else {
                list($column, $row) = array($startPoint->getColumn() + $i, $startPoint->getRow());
            }

            $anchorPoints[] = new Coordinate($row, $column);
        }

        return $anchorPoints;
    }

    /**
     * @param Coordinate[] $anchorPoints
     * @return bool
     */
    private function hasCollision(array $anchorPoints)
    {
        foreach ($anchorPoints as $point) {
            if ($point->getColumn() > BattlefieldCreator::MAX_COLUMNS ||
                $point->getRow() > BattlefieldCreator::MAX_ROWS
            ) {
                return true;
            }

            if ($this->fleet->hasShip($point)) {
                return true;
            }
        }

        return false;
    }
}