<?php

declare(strict_types = 1);

namespace Plattry\Database\Query;

/**
 * Common raw sql
 */
class Raw implements RawInterface
{
    protected string $value;

    public function __construct(string $raw)
    {
        $this->value = $raw;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}