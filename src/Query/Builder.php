<?php

declare(strict_types = 1);

namespace Plattry\Database\Query;

use Plattry\Database\ConnectionInterface;
use Plattry\Database\Result;

/**
 * Common query builder
 */
class Builder implements BuilderInterface
{
    /**
     * Statement type
     */
    protected const TYPE_INSERT = 1;

    protected const TYPE_DELETE = 2;

    protected const TYPE_UPDATE = 3;

    protected const TYPE_SELECT = 4;

    /**
     * Default SQL parts
     */
    protected const PARTS_DEFAULT = [
        "insert" => null,
        "delete" => [],
        "update" => [],
        "select" => [],
        "values" => [],
        "using" => [],
        "set" => [],
        "from" => [],
        "join" => [],
        "where" => [],
        "groupBy" => [],
        "having" => [],
        "orderBy" => [],
        "limit" => null,
        "offset" => null,
        "returning" => []
    ];

    /**
     * The SQL parts supported by each statement type
     */
    protected const PART_INSERT = [];

    protected const PART_DELETE = [];

    protected const PART_UPDATE = [];

    protected const PART_SELECT = [];

    /**
     * Grammar class
     */
    protected const GRAMMAR = Grammar::class;

    /**
     * Current statement type
     * @var int
     */
    protected int $type = self::TYPE_SELECT;

    /**
     * Current SQL parts
     * @var array
     */
    protected array $parts = self::PARTS_DEFAULT;

    /**
     * Database connection
     * @var ConnectionInterface
     */
    protected ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function insert(string $insert): static
    {
        $this->type = static::TYPE_INSERT;

        $this->parts["insert"] = $insert;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(string ...$delete): static
    {
        $this->type = static::TYPE_DELETE;

        $this->parts["delete"] = array_merge(
            $this->parts["delete"],
            $delete
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function update(string ...$update): static
    {
        $this->type = static::TYPE_UPDATE;

        $this->parts["update"] = array_merge(
            $this->parts["update"],
            $update
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function select(string ...$select): static
    {
        $this->type = static::TYPE_SELECT;

        $this->parts["select"] = array_merge(
            $this->parts["select"],
            $select
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function values(array $values, bool $multi = false): static
    {
        $this->parts["values"] = array_merge(
            $this->parts["values"],
            !$multi ? [$values] : $values
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function using(string ...$using): static
    {
        $this->parts["using"] = array_merge(
            $this->parts["using"],
            $using
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set(string|array $field, mixed $value = null): static
    {
        $this->parts["set"] = array_merge(
            $this->parts["set"],
            is_string($field) ? [$field => $value] : $field
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function from(string ...$from): static
    {
        $this->parts["from"] = array_merge(
            $this->parts["from"],
            $from
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function join(string $table, string|array $left, string $operator = null, string $right = null, string $type = Grammar::INNER): static
    {
        $this->parts["join"][] = is_string($left) ? [$table, $left, $operator, $right, $type] : [$table, $left, null, null, $type];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(string|array $left, string $operator = null, mixed $right = null, string $type = Grammar::AND): static
    {
        $this->parts["where"][] = is_string($left) ? [$left, $operator, $right, $type] : [$left, null, null, $type];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string ...$groupBy): static
    {
        $this->parts["groupBy"] = array_merge(
            $this->parts["groupBy"],
            $groupBy
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function having(string|array $left, string $operator = null, mixed $right = null, string $type = Grammar::AND): static
    {
        $this->parts["having"][] = is_string($left) ? [$left, $operator, $right, $type] : [$left, null, null, $type];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string|array $field, string $direction = Grammar::ASC): static
    {
        $this->parts["orderBy"] = array_merge(
            $this->parts["orderBy"],
            is_string($field) ? [[$field, $direction]] : $field
        );
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): static
    {
        $this->parts["limit"] = $limit;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): static
    {
        $this->parts["offset"] = $offset;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function returning(string ...$returning): static
    {
        $this->parts["returning"] = array_merge(
            $this->parts["returning"],
            $returning
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSql(): array
    {
        $statement = $parameter = [];
        
        $parts = match($this->type) {
            static::TYPE_INSERT => static::PART_INSERT,
            static::TYPE_DELETE => static::PART_DELETE,
            static::TYPE_UPDATE => static::PART_UPDATE,
            static::TYPE_SELECT => static::PART_SELECT
        };

        foreach($parts as $item) {
            if (
                is_null($this->parts[$item]) || 
                (is_array($this->parts[$item]) && empty($this->parts[$item]))
            ) {
                continue;
            }

            $result = static::GRAMMAR::$item($this->parts[$item]);
            if (is_string($result)) {
                $statement[] = $result;
                continue;
            }

            [$statement[], $childParameter] = $result;
            array_push($parameter, ...$childParameter);
        }

        return [implode(" ", $statement), $parameter];
    }

    /**
     * @inheritDoc
     */
    public function execute(): Result
    {
        [$sql, $binding] = $this->getSql();

        return $this->connection->execute($sql, $binding);
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        $this->parts = static::PARTS_DEFAULT;

        return $this;
    }
}
