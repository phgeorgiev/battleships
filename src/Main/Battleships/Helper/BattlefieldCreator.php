<?php

namespace Battleships\Helper;

use Battleships\Game\Battlefield;
use Battleships\Game\Fleet;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * @var ShotsManager
     */
    private $shotsManager;

    public function __construct(Battlefield $battlefield, BattlefieldDeployer $deployManager, ShotsManager $shotsManager)
    {
        $this->battlefield = $battlefield;
        $this->deployer = $deployManager;
        $this->shotsManager = $shotsManager;
    }

    public function create()
    {
        if (!$this->battlefield->isDeployed()) {
            $this->deployer->deploy();
        }
    }

    public function createFromSession(Session $session)
    {
        if ($session->has('fleet')) {
            $fleet = unserialize($session->get('fleet'));
            $shots = unserialize($session->get('shots'));
            $hits = unserialize($session->get('hits'));

            $this->battlefield->setFleet($fleet);
            $this->shotsManager->setAllShots($shots);
            $this->shotsManager->setHits($hits);
        }

        $this->create();
    }
}