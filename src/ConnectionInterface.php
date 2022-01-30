<?php

declare(strict_types = 1);

namespace Plattry\Database;

use Plattry\Database\Query\BuilderInterface;

/**
 * Database connection
 */
interface ConnectionInterface
{
    /**
     * Executes an SQL statement and return a Result object.
     * @param string $sql SQL statement.
     * @param array $bindings Parameters.
     * @return Result
     * @throws \RuntimeException
     */
    public function execute(string $sql, array $bindings = []): Result;

    /**
     * Get transaction status, true if is in a transaction, or false.
     * @return bool
     */
    public function getTransactionStatus(): bool;

    /**
     * Start a transaction.
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * Rollback a transaction.
     * @return bool
     */
    public function rollbackTransaction(): bool;

    /**
     * Commits a transaction.
     * @return bool
     */
    public function commitTransaction(): bool;

    /**
     * Get Pdo instance.
     * @return \PDO
     */
    public function getPdo(): \PDO;

    /**
     * Create query builder.
     */
    public function createQuery(): BuilderInterface;
}
