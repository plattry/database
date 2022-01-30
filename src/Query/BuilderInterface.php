<?php

declare(strict_types = 1);

namespace Plattry\Database\Query;

use Plattry\Database\Result;

/**
 * Query builder
 */
interface BuilderInterface
{
    /**
     * @param string $insert
     * @return static
     */
    public function insert(string $insert): static;

    /**
     * @param string ...$delete
     * @return static
     */
    public function delete(string ...$delete): static;

    /**
     * @param string ...$update
     * @return static
     */
    public function update(string ...$update): static;

    /**
     * @param string ...$select
     * @return static
     */
    public function select(string ...$select): static;

    /**
     * @param array $values
     * @param boolean $multi
     * @return static
     */
    public function values(array $values, bool $multi = false): static;

    /**
     * @param string ...$using
     * @return static
     */
    public function using(string ...$using): static;

    /**
     * @param string|array $field
     * @param mixed $value
     * @return static
     */
    public function set(string|array $field, mixed $value = null): static;

    /**
     * @param string ...$from
     * @return static
     */
    public function from(string ...$from): static;

    /**
     * @param string $table
     * @param string|array $left
     * @param string|null $operator
     * @param string|null $right
     * @param [type] $type
     * @return static
     */
    public function join(string $table, string|array $left, string $operator = null, string $right = null, string $type = Grammar::INNER): static;

    /**
     * @param string|array $left
     * @param string|null $operator
     * @param mixed $right
     * @param [type] $type
     * @return static
     */
    public function where(string|array $left, string $operator = null, mixed $right = null, string $type = Grammar::AND): static;

    /**
     * @param string ...$groupBy
     * @return static
     */
    public function groupBy(string ...$groupBy): static;

    /**
     * @param string|array $left
     * @param string|null $operator
     * @param mixed $right
     * @param [type] $type
     * @return static
     */
    public function having(string|array $left, string $operator = null, mixed $right = null, string $type = Grammar::AND): static;

    /**
     * @param string|array $field
     * @param [type] $direction
     * @return static
     */
    public function orderBy(string|array $field, string $direction = Grammar::ASC): static;

    /**
     * @param integer $limit
     * @return static
     */
    public function limit(int $limit): static;

    /**
     * @param integer $offset
     * @return static
     */
    public function offset(int $offset): static;

    /**
     * @param string ...$returning
     * @return static
     */
    public function returning(string ...$returning): static;

    /**
     * @return array
     */
    public function getSql(): array;

    /**
     * @return Result
     */
    public function execute(): Result;

    /**
     * @return static
     */
    public function reset(): static;
}
