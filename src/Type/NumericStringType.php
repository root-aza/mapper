<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Type;

use TypeLang\Mapper\Exception\Mapping\InvalidValueException;
use TypeLang\Mapper\Runtime\Context\LocalContext;

final class NumericStringType extends NamedType
{
    public function match(mixed $value, LocalContext $context): bool
    {
        return \is_string($value) && \is_numeric($value);
    }

    /**
     * @throws InvalidValueException
     */
    public function cast(mixed $value, LocalContext $context): string
    {
        if ($this->match($value, $context)) {
            /** @var numeric-string */
            return $value;
        }

        throw InvalidValueException::becauseInvalidValueGiven(
            value: $value,
            expected: $this->getTypeStatement($context),
            context: $context,
        );
    }
}