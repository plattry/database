<?php

declare(strict_types = 1);

namespace Plattry\Database;

/**
 * Connection factory
 */
class ConnectionFactory
{
    /**
     * Create a database connection.
     * @param array $params
     * @param array $options
     * @return ConnectionInterface
     */
    public static function create(array $params, array $options = []): ConnectionInterface
    {
        $dsn = match($params["driver"]) {
            "pdo_pgsql" => static::createPgsqlDsn($params)
        };
        
        return new Connection($dsn, $params["username"] ?? null, $params["password"] ?? null, $options);
    }

    /**
     * Create pdo_pgsql dsn.
     * @param array $params
     * @return string
     */
    public static function createPgsqlDsn(array $params): string
    {
        $dsn = 'pgsql:';

        if (isset($params['host']) && $params['host'] !== '') {
            $dsn .= "host={$params["host"]};";
        }

        if (isset($params['port']) && $params['port'] !== '') {
            $dsn .= "port={$params["port"]};";
        }

        if (isset($params['dbname']) && $params['dbname'] !== '') {
            $dsn .= "dbname={$params["dbname"]};";
        }

        if (isset($params['sslmode']) && $params['sslmode'] !== '') {
            $dsn .= "sslmode={$params["sslmode"]};";
        }

        return $dsn;
    }
}
