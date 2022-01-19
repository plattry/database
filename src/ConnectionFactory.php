<?php

declare(strict_types = 1);

namespace Plattry\Database;

/**
 * Class ConnectionFactory
 * @package Plattry\Database
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
            "pdo_mysql" => static::createMysqlDsn($params),
            "pdo_pgsql" => static::createPgsqlDsn($params)
        };
        
        return new Connection($dsn, $params["username"] ?? null, $params["password"] ?? null, $options);
    }

    /**
     * Create pdo_mysql dsn.
     * @param array $params
     * @return string
     */
    protected static function createMysqlDsn(array $params): string
    {
        $dsn = "mysql:";

        if (isset($params["host"]) && $params['host'] !== '') {
            $dsn .= "host={$params["host"]};";
        }

        if (isset($params["port"]) && $params['port'] !== '') {
            $dsn .= "port={$params["port"]};";
        }

        if (isset($params["unix_socket"]) && $params['unix_socket'] !== '') {
            $dsn .= "unix_socket={$params["unix_socket"]};";
        }

        if (isset($params["dbname"]) && $params['dbname'] !== '') {
            $dsn .= "dbname={$params["dbname"]};";
        }

        if (isset($params["charset"]) && $params['charset'] !== '') {
            $dsn .= "charset={$params["charset"]};";
        }

        return $dsn;
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
