<?php

declare(strict_types = 1);

namespace Plattry\Database;

use Plattry\Database\Collection\Collector;
use Plattry\Database\Exception\RunStatementException;

/**
 * Interface ConnectionAbstract
 * @package Plattry\Database
 */
interface ConnectionInterface
{
    /**
     * Execute an SQL statement and return the number of affected rows.
     * @param string $sql SQL statement.
     * @param array $bindings Parameters.
     * @return int
     * @throws RunStatementException
     */
    public function execute(string $sql, array $bindings = []): int;

    /**
     * Executes an SQL statement and return a result set as a Collector object.
     * @param string $sql SQL statement.
     * @param array $bindings Parameters.
     * @return Collector
     * @throws RunStatementException
     */
    public function query(string $sql, array $bindings = []): Collector;
}
