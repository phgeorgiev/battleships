<?php

namespace Battleships\Formatter;

use Battleships\Game\Battlefield;
use Battleships\Game\Coordinate;
use Battleships\Helper\BattlefieldCreator;
use Battleships\Helper\ShotsManager;
use Helper\AlphabetHelper;

class BattlefieldFormatter
{

    const NOT_SHOOT = '.';
    const MISS = '-';
    const HIT = 'X';
    const EMPTY_FIELD = ' ';

    /**
     * @var Battlefield
     */
    private $battlefield;

    /**
     * @var ShotsManager
     */
    private $shootManager;

    public function __construct(Battlefield $battlefield, ShotsManager $shootManager)
    {
        $this->battlefield = $battlefield;
        $this->shootManager = $shootManager;
    }

    public function format($debug = false)
    {
        $battlefieldElements = $this->generateElements($debug);

        return $this->display($battlefieldElements);
    }

    private function generateElements($debug = false)
    {
        $elements = [];

        for ($row = BattlefieldCreator::MIN_ROWS; $row <= BattlefieldCreator::MAX_ROWS; $row++) {
            for ($column = BattlefieldCreator::MIN_COLUMNS; $column <= BattlefieldCreator::MAX_COLUMNS; $column++) {
                $coordinate = new Coordinate($row, $column);

                $elements[$row][] = $this->getElementSign($coordinate, $debug);
            }
        }

        return $elements;
    }

    private function getElementSign(Coordinate $coordinate, $debug = false)
    {
        if ($debug) {
            if ($this->battlefield->getFleet()->hasShip($coordinate)) {
                if (!$this->shootManager->hasHit($coordinate)) {
                    return self::HIT;
                }
            }

            return self::EMPTY_FIELD;
        }

        if ($this->shootManager->hasShot($coordinate)) {
            if ($this->shootManager->hasHit($coordinate)) {
                return self::HIT;
            }

            return self::MISS;
        }

        return self::NOT_SHOOT;
    }

    private function display(array $battlefieldElements)
    {
        $renderString = '';

        $renderString .= self::EMPTY_FIELD . self::EMPTY_FIELD;
        $renderString .= implode(range(BattlefieldCreator::MIN_COLUMNS, BattlefieldCreator::MAX_COLUMNS), ' ') . "\n";

        $rowLetter = AlphabetHelper::getChar(BattlefieldCreator::MIN_COLUMNS);
        foreach ($battlefieldElements as $row) {
            $renderString .= $rowLetter++ . self::EMPTY_FIELD . implode($row, ' ') . "\n";
        }

        return $renderString;
    }
}