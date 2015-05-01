<?php

namespace Battleships\Game;

abstract class Ship
{

    /**
     * @var int
     */
    protected $size;

    /**
     * @var Coordinate[]
     */
    protected $anchorPoints;

    /**
     * Get ship size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param array $anchorPoints
     */
    public function setAnchorPoints(array $anchorPoints)
    {
        $this->anchorPoints = $anchorPoints;
    }

    /**
     * Get array of anchor points represented in columns and rows coordinates
     *
     * @return array
     */
    public function getAnchorPoints()
    {
        return $this->anchorPoints;
    }

    /**
     * Is ship anchored
     *
     * @return bool
     */
    public function isAnchored()
    {
        return count($this->anchorPoints) == $this->size;
    }

    /**
     * Check for existing anchor point
     *
     * @param Coordinate $coordinate
     * @return bool
     */
    public function hasAnchorPoint(Coordinate $coordinate)
    {
        if ($this->isAnchored()) {
            return in_array($coordinate, $this->anchorPoints);
        }

        return false;
    }
}