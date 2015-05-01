<?php

namespace Battleships\Input;

use Battleships\Game\Coordinate;
use Exception\DebugException;
use Exception\NoInputException;
use Exception\ValidationException;
use Helper\AlphabetHelper;

class InputHandler
{

    /**
     * @var InputValidation
     */
    private $inputValidation;

    /**
     * @param InputValidation $inputValidation
     */
    public function __construct(InputValidation $inputValidation)
    {
        $this->inputValidation = $inputValidation;
    }

    /**
     * @param $input
     * @return Coordinate
     * @throws DebugException
     * @throws NoInputException
     * @throws ValidationException
     */
    public function handle($input)
    {
        $input = trim($input);

        if (!$input) {
            throw new NoInputException();
        }

        if ($this->inputValidation->isDebugValid($input)) {
            throw new DebugException();
        }

        if (!$this->inputValidation->isValid($input)) {
            throw new ValidationException();
        }

        $char = substr($input, 0, 1);
        $number = substr($input, 1);

        $row = AlphabetHelper::getPosition($char);
        $column = intval($number);

        return new Coordinate($row, $column);
    }
}