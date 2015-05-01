<?php

namespace Battleships\Game;

class Battlefield
{

    /**
     * @var Fleet
     */
    private $fleet;

    /**
     * @var Coordinate[]
     */
    private $shootPoints;

    /**
     * @param Fleet $fleet
     */
    public function __construct(Fleet $fleet)
    {
        $this->fleet = $fleet;
        $this->shootPoints = array();
    }

    /**
     * @return bool
     */
    public function isDeployed()
    {
        return $this->fleet->isDeployed();
    }

    /**
     * @param Fleet $fleet
     */
    public function setFleet(Fleet $fleet)
    {
        $this->fleet = $fleet;
    }

    /**
     * @return Fleet
     */
    public function getFleet()
    {
        return $this->fleet;
    }
}