<?php

namespace Controller;

use Battleships\Formatter\BattlefieldFormatter;
use Battleships\Game\Battlefield;
use Battleships\Helper\BattlefieldCreator;
use Battleships\Helper\ShootManager;
use Battleships\Input\InputHandler;
use Exception\DebugException;
use Exception\NoInputException;
use Exception\ValidationException;
use Framework\Console\Controller;
use Output\ConsoleOutputManager;

class ConsoleController extends Controller
{

    /**
     * @var BattlefieldCreator
     */
    private $battlefieldCreator;

    /**
     * @var BattlefieldFormatter
     */
    private $formatter;

    /**
     * @var InputHandler
     */
    private $inputHandler;

    /**
     * @var ShootManager
     */
    private $shootManager;

    /**
     * @var Battlefield
     */
    private $battlefield;

    /**
     * @param BattlefieldCreator $battlefieldCreator
     * @param BattlefieldFormatter $formatter
     * @param InputHandler $handler
     * @param ShootManager $shootManager
     * @param Battlefield $battlefield
     */
    public function __construct(BattlefieldCreator $battlefieldCreator,
                                BattlefieldFormatter $formatter,
                                InputHandler $handler,
                                ShootManager $shootManager,
                                Battlefield $battlefield)
    {
        $this->battlefieldCreator = $battlefieldCreator;
        $this->formatter = $formatter;
        $this->inputHandler = $handler;
        $this->shootManager = $shootManager;
        $this->battlefield = $battlefield;
    }

    public function newGameAction()
    {
        $this->battlefieldCreator->create();

        return $this->render($this->formatter->format());
    }

    public function submitAction($input)
    {
        try {
            $coordinate = $this->inputHandler->handle($input);
        } catch (NoInputException $e) {
            return $this->render($this->formatter->format());
        } catch (DebugException $e) {
            return $this->render($this->formatter->format(true));
        } catch (ValidationException $e) {
            return $this->render($this->formatter->format(), ConsoleOutputManager::ERROR_MESSAGE);
        }

        $hasHit = $this->shootManager->shoot($coordinate);
        if (!$hasHit) {
            return $this->render($this->formatter->format(), ConsoleOutputManager::MISS_MESSAGE);
        }

        if ($this->shootManager->isBattlefieldDestroyed()) {
            $shootNumber = $this->shootManager->getShootsCount();

            return $this->render($this->formatter->format(), ConsoleOutputManager::SUNK_MESSAGE, $shootNumber);
        }

        $ship = $this->battlefield->getFleet()->getShip($coordinate);
        if ($this->shootManager->isShipSunk($ship)) {
            return $this->render($this->formatter->format(), ConsoleOutputManager::SUNK_MESSAGE);
        }

        return $this->render($this->formatter->format(), ConsoleOutputManager::HIT_MESSAGE);
    }
}