<?php

namespace Output;

class ConsoleOutput
{

    private $flashMessage = '';

    private $formattedBattlefield = '';

    private $finishMessage = 'Well done! You complete the game in {$shots} shots.';

    private $shots;

    private $stop = false;

    public function setFlashMessage($message)
    {
        $this->flashMessage = $message;
    }

    public function setFormattedBattlefield($battlefield)
    {
        $this->formattedBattlefield = $battlefield;
    }

    public function setShotsNumber($shots)
    {
        $this->shots = $shots;
    }

    public function setStopStatus($status)
    {
        $this->stop = $status;
    }

    public function getStopStatus()
    {
        return $this->stop;
    }

    public function getOutput()
    {
        $output = '';

        $output .= $this->flashMessage . "\n\n";
        $this->flashMessage = '';

        $output .= $this->formattedBattlefield;

        if ($this->shots) {
            $this->finishMessage = str_replace('{$shots}', $this->shots, $this->finishMessage);
            $output .= $this->finishMessage;
        }

        return $output;
    }
}