<?php

namespace App\Entity\Data;

interface CommonDataCollectionInterface {

    public function get(int $index);

    public function getIterator(): \Iterator;
}