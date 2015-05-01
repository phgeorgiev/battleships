<?php

namespace Battleships\Helper;

use Battleships\Game\Battlefield;
use Battleships\Game\Coordinate;
use Battleships\Game\Ship;

class ShotsManager
{

    /**
     * @var array
     */
    private $allShots = array();

    /**
     * @var array
     */
    private $hits = array();

    /**
     * @var Battlefield
     */
    private $battlefield;

    /**
     * @param Battlefield $battlefield
     */
    public function __construct(Battlefield $battlefield)
    {
        $this->battlefield = $battlefield;
    }

    /**
     * @param $coordinate
     * @return bool
     */
    public function shoot($coordinate)
    {
        $this->allShots[] = $coordinate;

        if ($this->battlefield->getFleet()->hasShip($coordinate)) {
            if (!$this->hasHit($coordinate)) {
                $this->hits[] = $coordinate;
            }

            return true;
        }

        return false;
    }

    /**
     * @param Coordinate $coordinate
     * @return bool
     */
    public function hasShot(Coordinate $coordinate)
    {
        if (count($this->allShots)) {
            return in_array($coordinate, $this->allShots);
        }

        return false;
    }

    /**
     * @param Coordinate $coordinate
     * @return bool
     */
    public function hasHit(Coordinate $coordinate)
    {
        if (count($this->hits)) {
            return in_array($coordinate, $this->hits);
        }

        return false;
    }

    /**
     * @param array $hits
     */
    public function setHits(array $hits)
    {
        $this->hits = $hits;
    }

    /**
     * @return array
     */
    public function getHits()
    {
        return $this->hits;
    }

    public function setAllShots(array $shots)
    {
        $this->allShots = $shots;
    }

    /**
     * @return array
     */
    public function getAllShots()
    {
        return $this->allShots;
    }

    /**
     * @return int
     */
    public function getShotsCount()
    {
        return count($this->allShots);
    }

    /**
     * @param Ship $ship
     * @return bool
     */
    public function isShipSunk(Ship $ship)
    {
        foreach ($ship->getAnchorPoints() as $point) {
            if (!in_array($point, $this->hits)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isBattlefieldDestroyed()
    {
        foreach ($this->battlefield->getFleet() as $ship) {
            if (!$this->isShipSunk($ship)) {
                return false;
            }
        }

        return true;
    }
}