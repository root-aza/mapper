<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Type;

use TypeLang\Mapper\Exception\Mapping\InvalidValueException;
use TypeLang\Mapper\Runtime\Context\LocalContext;

class FloatType extends NamedType
{
    /**
     * @var non-empty-string
     */
    public const DEFAULT_TYPE_NAME = 'float';

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name = self::DEFAULT_TYPE_NAME)
    {
        parent::__construct($name);
    }

    public function match(mixed $value, LocalContext $context): bool
    {
        return \is_float($value) || \is_int($value);
    }

    /**
     * @throws InvalidValueException
     */
    public function cast(mixed $value, LocalContext $context): float
    {
        if ($this->match($value, $context)) {
            /** @var float|int $value */
            return (float) $value;
        }

        throw InvalidValueException::becauseInvalidValueGiven(
            value: $value,
            expected: $this->getTypeStatement($context),
            context: $context,
        );
    }
}
