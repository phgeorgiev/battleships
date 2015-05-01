<?php

namespace Battleships\Input;

use Battleships\Helper\BattlefieldCreator;
use Helper\AlphabetHelper;

class InputValidation
{

    const DEBUG_SHOW = 'show';

    /**
     * @param $input
     * @return bool
     */
    public function isValid($input)
    {
        $input = trim($input);

        $char = substr($input, 0, 1);
        $row = substr($input, 1);

        if ($this->isDebugValid($input)) {
            return true;
        }

        if (!AlphabetHelper::isInAlphabet($char)) {
            return false;
        }

        $column = AlphabetHelper::getPosition($char);
        if ($column < BattlefieldCreator::MIN_COLUMNS || $column > BattlefieldCreator::MAX_COLUMNS) {
            return false;
        }

        if (intval($row) < BattlefieldCreator::MIN_ROWS || intval($row) > BattlefieldCreator::MAX_ROWS) {
            return false;
        }

        return true;
    }

    /**
     * @param $input
     * @return bool
     */
    public function isDebugValid($input)
    {
        return strtolower($input) == self::DEBUG_SHOW;
    }
}