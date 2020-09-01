<?php
namespace core\lib\db;


use PDO;


class DBResult
{
    /** @var \PDOStatement $pdoStatement */
    private $pdoStatement;

    public function __construct(\PDOStatement $pdoStatement)
    {
        $this->pdoStatement = $pdoStatement;
        $this->pdoStatement->execute();
    }

    public function fetch()
    {
        return $this->pdoStatement->fetch();
    }

    public function fetchAll()
    {
        return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getError()
    {
        return $this->pdoStatement->errorInfo();
    }
}