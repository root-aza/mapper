<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Type;

use TypeLang\Mapper\Type\UnitEnumType\UnitEnumTypeDenormalizer;
use TypeLang\Mapper\Type\UnitEnumType\UnitEnumTypeNormalizer;

/**
 * @template-extends AsymmetricType<UnitEnumTypeNormalizer, UnitEnumTypeDenormalizer>
 */
class UnitEnumType extends AsymmetricType
{
    /**
     * @param class-string<\UnitEnum> $class
     * @param non-empty-list<non-empty-string> $cases
     */
    public function __construct(string $class, array $cases)
    {
        parent::__construct(
            normalizer: new UnitEnumTypeNormalizer($class),
            denormalizer: new UnitEnumTypeDenormalizer($class, $cases),
        );
    }
}
