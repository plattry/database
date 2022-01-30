<?php

declare(strict_types = 1);

namespace Plattry\Database\Query\Pgsql;

use Plattry\Database\Query\Grammar as BaseGrammar;

/**
 * Pgsql grammar parser
 */
class Grammar extends BaseGrammar
{
    /**
     * @inheritDoc
     */
    public static function delete(array $delete): string
    {
        return "DELETE FROM " . implode(", ", $delete);
    }
}
