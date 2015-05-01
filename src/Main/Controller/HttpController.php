<?php

namespace Controller;

use Battleships\Formatter\BattlefieldFormatter;
use Battleships\Game\Battlefield;
use Battleships\Helper\BattlefieldCreator;
use Battleships\Helper\ShotsManager;
use Battleships\Input\InputHandler;
use Exception\DebugException;
use Exception\NoInputException;
use Exception\ValidationException;
use Framework\Web\Controller;
use Output\ConsoleOutputManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class HttpController extends Controller
{

    /**
     * @var InputHandler
     */
    private $inputHandler;
    /**
     * @var BattlefieldFormatter
     */
    private $formatter;
    /**
     * @var BattlefieldCreator
     */
    private $battlefieldCreator;

    /**
     * @var ShotsManager
     */
    private $shootManager;

    /**
     * @var Battlefield
     */
    private $battlefield;

    /**
     * @param InputHandler $inputHandler
     * @param BattlefieldFormatter $formatter
     * @param BattlefieldCreator $battlefieldCreator
     * @param ShotsManager $shootManager
     * @param Battlefield $battlefield
     */
    public function __construct(InputHandler $inputHandler,
                                BattlefieldFormatter $formatter,
                                BattlefieldCreator $battlefieldCreator,
                                ShotsManager $shootManager,
                                Battlefield $battlefield)
    {
        $this->inputHandler = $inputHandler;
        $this->formatter = $formatter;
        $this->battlefieldCreator = $battlefieldCreator;
        $this->shootManager = $shootManager;
        $this->battlefield = $battlefield;
    }

    public function indexAction(Request $request)
    {
        /** @var Session $session */
        $session = $request->getSession();

        $this->battlefieldCreator->createFromSession($session);

        $input = $request->get('coord');

        try {
            $coordinate = $this->inputHandler->handle($input);
        } catch (NoInputException $e) {
            return $this->render('View/index.html', array('battlefield' => $this->formatter->format()));
        } catch (DebugException $e) {
            return $this->render('View/index.html', array('battlefield' => $this->formatter->format(true)));
        } catch (ValidationException $e) {
            $session->getFlashBag()->add('notice', ConsoleOutputManager::ERROR_MESSAGE);

            return $this->render('View/index.html', array('battlefield' => $this->formatter->format()));
        }

        $hasHit = $this->shootManager->shoot($coordinate);
        if (!$hasHit) {
            $session->getFlashBag()->add('notice', ConsoleOutputManager::MISS_MESSAGE);

            return $this->render('View/index.html', array('battlefield' => $this->formatter->format()));
        }

        if ($this->shootManager->isBattlefieldDestroyed()) {
            $session->invalidate(1);
            $shotsNumber = $this->shootManager->getShotsCount();

            return $this->render('View/finish.html', array('shots' => $shotsNumber));
        }

        $ship = $this->battlefield->getFleet()->getShip($coordinate);
        if ($this->shootManager->isShipSunk($ship)) {
            $session->getFlashBag()->add('notice', ConsoleOutputManager::SUNK_MESSAGE);

            return $this->render('View/index.html', array('battlefield' => $this->formatter->format()));
        }

        $session->getFlashBag()->add('notice', ConsoleOutputManager::HIT_MESSAGE);

        return $this->render('View/index.html', array('battlefield' => $this->formatter->format()));
    }
}