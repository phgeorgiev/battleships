<?php

namespace Battleships\Helper;

use Battleships\Game\Battlefield;
use Battleships\Game\Coordinate;
use Battleships\Game\Ship;

class ShootManager
{

    /**
     * @var array
     */
    private $allShoots;

    /**
     * @var array
     */
    private $hits;

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
        $this->allShoots[] = $coordinate;

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
    public function hasShoot(Coordinate $coordinate)
    {
        if (count($this->allShoots)) {
            return in_array($coordinate, $this->allShoots);
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
     * @return int
     */
    public function getShootsCount()
    {
        return count($this->allShoots);
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