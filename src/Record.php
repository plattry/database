<?php

declare(strict_types = 1);

namespace Plattry\Database;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

/**
 * Class Record
 * @package Plattry\Database
 */
class Record implements ArrayAccess, IteratorAggregate
{
    /**
     * Record constructor.
     * @param array $record
     */
    public function __construct(array $data)
    {
        $this->assignData($data);
    }

    /**
     * Assign data to property.
     * @param array $data
     * @return void
     */
    public function assignData(array $data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Determines whether an field is exist.
     * @param string $key Field name.
     * @return bool
     */
    public function exist(string $key): bool
    {
        return isset($this->$key);
    }

    /**
     * Get a field value.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->$key ?? null;
    }

    /**
     * Set a field value.
     * @param string $key Field name.
     * @param int|string $val Field value.
     * @return void
     */
    public function set(string $key, int|string $val): void
    {
        $this->$key = $val;
    }

    /**
     * Delete a field.
     * @param string $key Field name.
     * @return void
     */
    public function del(string $key): void
    {
        unset($this->$key);
    }

    /**
     * Convert object to array.
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->exist($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): false|int|string
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        $this->del($offset);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }
}