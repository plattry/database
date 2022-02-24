<?php

declare(strict_types = 1);

namespace Plattry\Database\Query\Pgsql;

use Plattry\Database\Query\Builder as BaseBuilder;

/**
 * Pgsql query builder
 */
class Builder extends BaseBuilder
{
    /**
     * The SQL parts supported by each statement type
     */
    protected const PART_INSERT = [
        "insert", "values", "conflict", "do", "set", "where", "returning"
    ];

    protected const PART_DELETE = [
        "delete", "using", "where", "returning"
    ];

    protected const PART_UPDATE = [
        "update", "set", "from", "where", "returning"
    ];

    protected const PART_SELECT = [
        "select", "from", "join", "where", "groupBy", "having", "orderBy", "limit", "offset"
    ];

    /**
     * @inheritDoc
     */
    protected const GRAMMAR = Grammar::class;
}
