<?php

declare(strict_types = 1);

namespace Plattry\Database;

use PDO;
use PDOStatement;
use Plattry\Database\Collection\Collector;
use Plattry\Database\Exception\RunStatementException;

/**
 * Class Connection
 * @package Plattry\Database
 */
class Connection implements ConnectionInterface
{
    /**
     * Database dsn
     * @var string
     */
    protected string $dsn = '';

    /**
     * Database username
     * @var string
     */
    protected string $username = '';

    /**
     * Database password
     * @var string
     */
    protected string $password = '';

    /**
     * Database attribute
     * @var array
     */
    protected array $option = [];

    /**
     * Pdo instance
     * @var PDO
     */
    protected PDO $instance;

    /**
     * Set pdo dsn.
     * @param string $dsn Pdo dsn.
     * @return void
     */
    public function setDsn(string $dsn): void
    {
        $this->dsn = $dsn;
    }

    /**
     * Set mysql userinfo.
     * @param string $username User name.
     * @param string $password User password.
     * @return void
     */
    public function setUser(string $username, string $password): void
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Set pdo attribute.
     * @param int $name Attribute name.
     * @param string $value Attribute value.
     * @return void
     */
    public function setOption(int $name, string $value): void
    {
        $this->option[$name] = $value;
    }

    /**
     * Connect Database.
     * @return void
     */
    public function connect(): void
    {
        $this->instance = new PDO(
            $this->dsn, $this->username, $this->password, $this->option
        );
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $bindings = []): int
    {
        $statement = $this->instance->prepare($sql);

        $this->bindValues($statement, $bindings);

        $this->runStatement($statement);

        return $statement->rowCount();
    }

    /**
     * @inheritDoc
     */
    public function query(string $sql, array $bindings = []): Collector
    {
        $statement = $this->instance->prepare($sql);

        $this->bindValues($statement, $bindings);

        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $this->runStatement($statement);

        return (new Collector($statement->fetchAll()));
    }

    /**
     * Binds a value to a parameter.
     * @param PDOStatement $statement Statement.
     * @param array $bindings Parameters.
     * @return void
     */
    protected function bindValues(PDOStatement $statement, array $bindings): void
    {
        foreach ($bindings as $key => $val) {
            $statement->bindValue(
                is_numeric($key) ? $key + 1 : $key,
                $val,
                is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }
    }

    /**
     * Executes a prepared statement.
     * @param PDOStatement $statement Statement.
     * @return void
     * @throws RunStatementException
     */
    protected function runStatement(PDOStatement $statement): void
    {
        $statement->execute();

        '00000' !== $statement->errorCode() &&
        throw new RunStatementException("Fail to execute SQL statement, ". implode(' ', $statement->errorInfo()));
    }

    /**
     * Checks if inside a transaction.
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->instance->inTransaction();
    }

    /**
     * Initiates a transaction.
     * @return bool
     */
    public function begin(): bool
    {
        return $this->instance->beginTransaction();
    }

    /**
     * Rolls back a transaction.
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->instance->rollBack();
    }

    /**
     * Commits a transaction.
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getPdo()->commit();
    }

    /**
     * Get Pdo instance.
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->instance;
    }
}
