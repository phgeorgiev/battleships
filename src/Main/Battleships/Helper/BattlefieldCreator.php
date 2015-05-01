<?php

namespace Battleships\Helper;

use Battleships\Game\Battlefield;

class BattlefieldCreator
{

    const MIN_COLUMNS = 1;
    const MIN_ROWS = 1;

    const MAX_COLUMNS = 10;
    const MAX_ROWS = 10;

    const DIRECTION_VERTICAL = 0;
    const DIRECTION_HORIZONTAL = 1;

    /**
     * @var Battlefield
     */
    private $battlefield;

    /**
     * @var BattlefieldDeployer
     */
    private $deployer;

    public function __construct(Battlefield $battlefield, BattlefieldDeployer $deployManager)
    {
        $this->battlefield = $battlefield;
        $this->deployer = $deployManager;
    }

    public function create()
    {
        if (!$this->battlefield->isDeployed()) {
            $this->deployer->deploy();
        }
    }
}