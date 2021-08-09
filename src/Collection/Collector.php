<?php

declare(strict_types = 1);

namespace Plattry\Database\Collection;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class Collector
 * @package Plattry\Database\Collection
 */
class Collector implements Countable, IteratorAggregate
{
    /**
     * Record collection
     * @var Record[]
     */
    protected array $records;

    /**
     * Collector constructor.
     * @param array $records
     */
    public function __construct(array $records)
    {
        $this->records = array_map(fn($record) => new Record($record), $records);
    }

    /**
     * Fetch one record.
     * @return false|Record
     */
    public function fetchOne(): false|Record
    {
        return next($this->records);
    }

    /**
     * Fetch all records.
     * @return Record[]
     */
    public function fetchAll(): array
    {
        return $this->records;
    }

    /**
     * Rewind the records point.
     * @return void
     */
    public function rewind(): void
    {
        reset($this->records);
    }

    /**
     * Convert object to array.
     * @return array
     */
    public function toArray(): array
    {
        return array_map(fn($record) => $record->toArray(), $this->records);
    }

    /**
     * Count records.
     * @return int
     */
    public function count(): int
    {
        return count($this->records);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->records);
    }
}
