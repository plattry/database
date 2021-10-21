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
     * Data model
     * @var string
     */
    protected string $model;

    /**
     * Collector constructor.
     * @param array $records
     * @param string $model
     */
    public function __construct(array $records, string $model = Record::class)
    {
        $this->records = $records;
        reset($this->records);

        $this->setModel($model);
    }

    /**
     * Get current data model.
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Set current data model.
     * @param string $model
     * @return void
     */
    public function setModel(string $model): void
    {
        !class_exists($model) &&
        throw new \InvalidArgumentException("Not found data model $model");

        $this->model = $model;
    }

    /**
     * Make a data model instance.
     * @param array $record
     * @return object
     */
    protected function makeModel(array $record): object
    {
        return new $this->model($record);
    }

    /**
     * Fetch one record.
     * @return bool|object
     */
    public function fetchOne(): bool|object
    {
        $record = current($this->records);
        if ($record === false)
            return false;

        next($this->records);

        return $this->makeModel($record);
    }

    /**
     * Fetch all records.
     * @return Record[]
     */
    public function fetchAll(): array
    {
        return array_map(fn($record) => $this->makeModel($record), $this->records);
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
        return $this->records;
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
        return new ArrayIterator($this->fetchAll());
    }
}
