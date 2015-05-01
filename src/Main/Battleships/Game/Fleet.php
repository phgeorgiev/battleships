<?php

namespace Battleships\Game;

class Fleet implements \IteratorAggregate, \Countable
{

    /**
     * @var Ship[]
     */
    private $fleet;

    /**
     * @param array $fleet
     */
    public function setFleet(array $fleet)
    {
        $this->fleet = $fleet;
    }

    /**
     * @return Ship[]
     */
    public function getFleet()
    {
        return $this->fleet;
    }

    /**
     * @return bool
     */
    public function isDeployed()
    {
        foreach ($this->fleet as $ship) {
            if (!$ship->isAnchored()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Coordinate $coordinate
     * @return bool
     */
    public function hasShip(Coordinate $coordinate)
    {
        foreach ($this->fleet as $ship) {
            if ($ship->hasAnchorPoint($coordinate)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Coordinate $coordinate
     * @return Ship|null
     */
    public function getShip(Coordinate $coordinate)
    {
        foreach ($this->fleet as $ship) {
            if ($ship->hasAnchorPoint($coordinate)) {
                return $ship;
            }
        }

        return null;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fleet);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->fleet);
    }
}