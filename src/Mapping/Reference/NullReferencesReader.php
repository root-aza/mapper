<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Mapping\Reference;

final class NullReferencesReader implements ReferencesReaderInterface
{
    public function getUseStatements(\ReflectionClass $class): array
    {
        return [];
    }
}
