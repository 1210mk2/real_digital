<?php

namespace App\System\Db;

use \PDO;
use \PDOStatement;

class DbRowIterator implements \Iterator
{
    protected PDOStatement $statement;
    protected $fetch_mode;

    protected int $key;
    protected bool $valid = true;

    protected $result;


    public function __construct(PDOStatement $statement, $fetch_mode = PDO::FETCH_BOTH)
    {
        $this->statement  = $statement;
        $this->fetch_mode = $fetch_mode;
    }

    public function current()
    {
        return $this->result;
    }

    public function next()
    {
        $this->key++;
    }

    public function key()
    {
        return $this->key;
    }

    public function valid()
    {
        $this->result = $this->statement->fetch($this->fetch_mode, PDO::FETCH_ORI_ABS, $this->key);
        if (false === $this->result) {
            $this->valid = false;
        }

        return $this->valid;
    }

    public function rewind()
    {
        $this->key = 0;
    }
}