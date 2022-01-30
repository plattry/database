<?php

declare(strict_types = 1);

namespace Plattry\Database\Query;

/**
 * Grammar parser of query builder
 */
interface GrammarInterface
{
    /**
     * Sort type
     */
    public const ASC = "ASC";
    public const DESC = "DESC";

    /**
     * Connector type
     */
    public const AND = "AND";
    public const OR = "OR";

    /**
     * Join type
     */
    public const LEFT = "LEFT";
    public const RIGHT = "RIGHT";
    public const INNER = "INNER";

    /**
     * Operator type
     */
    public const GT = ">";
    public const GTE = ">=";
    public const EQ = "=";
    public const NE = "=";
    public const LTE = "<=";
    public const LT = "<";
    public const LIKE = "LIKE";
    public const IN = "IN";

    /**
     * @param string $insert
     * @return string
     */
    public static function insert(string $insert): string;

    /**
     * @param array $delete
     * @return string
     */
    public static function delete(array $delete): string;

    /**
     * @param array $update
     * @return string
     */
    public static function update(array $update): string;

    /**
     * @param array $fields
     * @return string
     */
    public static function select(array $fields): string;

    /**
     * @param array $values
     * @return array
     */
    public static function values(array $values): array;

    /**
     * @param array $using
     * @return string
     */
    public static function using(array $using): string;

    /**
     * @param array $set
     * @return array
     */
    public static function set(array $set): array;

    /**
     * @param array $from
     * @return string
     */
    public static function from(array $from): string;

    /**
     * @param array $join
     * @param boolean $isChildren
     * @return string
     */
    public static function join(array $join, bool $isChildren = false): string;

    /**
     * @param array $where
     * @param boolean $isChildren
     * @return array
     */
    public static function where(array $where, bool $isChildren = false): array;

    /**
     * @param array $groupBy
     * @return string
     */
    public static function groupBy(array $groupBy): string;

    /**
     * @param array $having
     * @param boolean $isChildren
     * @return array
     */
    public static function having(array $having, bool $isChildren = false): array;

    /**
     * @param array $orderBy
     * @return string
     */
    public static function orderBy(array $orderBy): string;

    /**
     * @param integer $limit
     * @return array
     */
    public static function limit(int $limit): array;

    /**
     * @param integer $offset
     * @return array
     */
    public static function offset(int $offset): array;

    /**
     * @param array $returning
     * @return string
     */
    public static function returning(array $returning): string;
}
