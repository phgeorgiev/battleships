<?php

namespace Output;

class ConsoleOutputManager
{

    const ERROR_MESSAGE = '*** Error ***';
    const HIT_MESSAGE = '*** Hit ***';
    const SUNK_MESSAGE = '*** Sunk ***';
    const MISS_MESSAGE = '*** Miss ***';


    public function __construct(ConsoleOutput $output)
    {
        $this->output = $output;
    }

    public function setFlashMessage($message)
    {
        $this->output->setFlashMessage($message);
    }

    public function setFormattedBattlefield($battlefield)
    {
        $this->output->setFormattedBattlefield($battlefield);
    }

    public function setShotsNumber($shots)
    {
        $this->output->setShotsNumber($shots);
    }

    public function setOutputExitStatus($status)
    {
        $this->output->setStopStatus($status);
    }

    public function output()
    {
        return $this->output;
    }
}