<?php

namespace App\System\Db;

use \PDO;
use \PDOStatement;
use function PHPUnit\Framework\returnArgument;

class Db implements DbInterface
{

    public const CONFIG_DB_FILE = 'config' . DIRECTORY_SEPARATOR . 'database.php';

    private PDO $pdo;

    public function __construct()
    {

    }

    public function getPdo(): PDO
    {
        if (!isset($this->pdo)) {

            $config = $this->getConfig();
            $dsn        = $config['driver'] . ":host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['database'] . ";";
            $user       = $config['user'];
            $password   = $config['password'];
            $options    = $config['options'] ?? null;

            $this->pdo = new PDO($dsn, $user, $password, $options);
        }

        return $this->pdo;
    }

    private function getConfig()
    {
        $path = self::CONFIG_DB_FILE;

        return include $path;
    }

    public function get(string $sql, ?array $params): array
    {
        $statement = $this->prepareStatement($sql);
        $this->executeStatement($statement, $params);

        return $statement->fetchAll(self::FETCH_MODE);

    }

    public function getIterator(string $sql, ?array $params): \Iterator
    {
        $statement = $this->prepareStatement($sql);
        $this->executeStatement($statement, $params);

        return $this->getFetchIterator($statement);
    }

    public function execute(string $sql, ?array $params): void
    {
        $statement = $this->prepareStatement($sql);
        $this->executeStatement($statement, $params);
    }

    private function prepareStatement(string $sql): PDOStatement
    {
        return $this->getPdo()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    }

    private function executeStatement(PDOStatement &$statement, ?array $params): void
    {
        $result = $statement->execute($params);
        if ($result === false) {
            $error   = $statement->errorInfo();
            $message = "[" . $error[0] . "]: " . $error[2];
            throw new \Exception("SQL result is false: $message");
        }
    }

    private function getFetchIterator(PDOStatement $statement): \Iterator
    {
        return new DbRowIterator($statement, self::FETCH_MODE);
    }
}