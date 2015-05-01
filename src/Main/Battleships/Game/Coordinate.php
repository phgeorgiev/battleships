<?php

namespace Battleships\Game;

class Coordinate
{

    /**
     * @var int
     */
    private $column;

    /**
     * @var int
     */
    private $row;

    /**
     * @param int $column
     * @param int $row
     */
    public function __construct($row, $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }
}