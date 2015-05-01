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
     * @return Fleet
     */
    public function getFleet()
    {
        return $this->fleet;
    }
}