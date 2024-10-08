<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Path\Entry;

interface EntryInterface extends \Stringable
{
    /**
     * Returns string representation of the path entry.
     */
    public function __toString(): string;
}
