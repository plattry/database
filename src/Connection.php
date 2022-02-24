<?php

declare(strict_types = 1);

namespace Plattry\Database;

use Plattry\Database\Query\BuilderInterface;
use Plattry\Database\Query\Pgsql\Builder;

/**
 * Database Connection
 */
class Connection implements ConnectionInterface
{
    /**
     * Database dsn
     * @var string
     */
    protected string $dsn;

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
     * @var string
     */
    protected string $queryClass;

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

        $driver = strstr($dsn, ":", true);
        $this->queryClass = match($driver) {
            "pgsql" => Builder::class
        };
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $bindings = []): Result
    {
        $statement = $this->pdo->prepare($sql);

        foreach ($bindings as $key => $val) {
            $type = match (get_debug_type($val)) {
                'null' => \PDO::PARAM_NULL,
                'bool' => \PDO::PARAM_BOOL,
                'int', 'float' => \PDO::PARAM_INT,
                'string' => \PDO::PARAM_STR
            };
            $statement->bindValue(is_numeric($key) ? $key + 1 : $key, $val, $type);
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

    /**
     * @inheritDoc
     */
    public function createQuery(): BuilderInterface
    {
        return new $this->queryClass($this);
    }
}
