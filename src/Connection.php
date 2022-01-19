<?php

declare(strict_types = 1);

namespace Plattry\Database;

/**
 * Class Connection
 * @package Plattry\Database
 */
class Connection implements ConnectionInterface
{
    /**
     * Database dsn
     * @var string|null
     */
    protected string|null $dsn;

    /**
     * Database username
     * @var string|null
     */
    protected string|null $username;

    /**
     * Database password
     * @var string|null
     */
    protected string|null $password;

    /**
     * Database options
     * @var array|null
     */
    protected array|null $options;

    /**
     * Pdo instance
     * @var \PDO
     */
    protected \PDO $pdo;

    /**
     * @param string $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array|null $options
     */
    public function __construct(
        string $dsn,
        string $username = null,
        string $password = null,
        array $options = null
    ) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $bindings = []): Result
    {
        $statement = $this->pdo->prepare($sql);

        foreach ($bindings as $key => $val) {
            $statement->bindValue(
                is_numeric($key) ? $key + 1 : $key,
                $val,
                is_int($val) ? \PDO::PARAM_INT : \PDO::PARAM_STR
            );
        }

        $ret = $statement->execute();
        !$ret &&
        throw new \RuntimeException("Fail to execute SQL statement");

        '00000' !== $statement->errorCode() &&
        throw new \RuntimeException("Fail to execute SQL statement, ". implode(' ', $statement->errorInfo()));

        return new Result(
            $statement->rowCount(),
            $statement->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    /**
     * @inheritDoc
     */
    public function getTransactionStatus(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function rollbackTransaction(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * @inheritDoc
     */
    public function commitTransaction(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @inheritDoc
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
