<?php

namespace Event;

use Battleships\Game\Battlefield;
use Battleships\Helper\ShotsManager;

class PostControllerListener
{

    /**
     * @var Battlefield
     */
    private $battlefield;
    /**
     * @var ShotsManager
     */
    private $shotsManager;

    public function __construct(Battlefield $battlefield, ShotsManager $shotsManager)
    {
        $this->battlefield = $battlefield;
        $this->shotsManager = $shotsManager;
    }

    public function onPostController(ResponseEvent $event)
    {
        $session = $event->getSession();

        if ($session->has('gameFinished')) {
            $session->clear();

            return;
        }

        $fleet = $this->battlefield->getFleet();
        $shots = $this->shotsManager->getAllShots();
        $hits = $this->shotsManager->getHits();

        $session->set('fleet', serialize($fleet));
        $session->set('shots', serialize($shots));
        $session->set('hits', serialize($hits));
    }
}