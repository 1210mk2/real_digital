<?php

namespace App\System\Db;

interface DbInterface
{
    const FETCH_MODE = \PDO::FETCH_OBJ;

    public function getPdo(): \PDO;

    public function get(string $sql, ?array $params): array;
    public function getIterator(string $sql, ?array $params): \Iterator;

}