<?php

namespace Framework\Console;

use Output\ConsoleOutputManager;
use Symfony\Component\Config\Definition\Exception\Exception;

abstract class Controller
{

    /**
     * @var ConsoleOutputManager
     */
    protected $outputManager;

    /**
     * @param ConsoleOutputManager $output
     */
    public function setOutputManager(ConsoleOutputManager $output)
    {
        $this->outputManager = $output;
    }

    public function render($battlefield, $message = '', $shots = null)
    {
        if (!$battlefield) {
            throw new \InvalidArgumentException();
        }

        $this->outputManager->setFlashMessage($message);
        $this->outputManager->setFormattedBattlefield($battlefield);

        if ($shots) {
            $this->outputManager->setOutputExitStatus(true);
            $this->outputManager->setShotsNumber($shots);
        }

        return $this->outputManager->output();
    }
}