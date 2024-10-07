<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Mapping\Driver;

use TypeLang\Mapper\Exception\TypeNotFoundException;
use TypeLang\Mapper\Mapping\Metadata\ClassMetadata;
use TypeLang\Mapper\Mapping\Metadata\PropertyMetadata;
use TypeLang\Mapper\Type\Repository\RepositoryInterface;
use TypeLang\Reader\Exception\ReaderExceptionInterface;
use TypeLang\Reader\PropertyReaderInterface as PropertyTypeReaderInterface;
use TypeLang\Reader\ReflectionReader as ReflectionTypeReader;

final class ReflectionDriver extends Driver
{
    private readonly PropertyTypeReaderInterface $properties;

    public function __construct(
        private readonly DriverInterface $delegate = new NullDriver(),
    ) {
        $this->properties = new ReflectionTypeReader();
    }

    /**
     * @throws ReaderExceptionInterface
     */
    public function getClassMetadata(\ReflectionClass $class, RepositoryInterface $types): ClassMetadata
    {
        $metadata = $this->delegate->getClassMetadata($class, $types);

        foreach ($class->getProperties() as $property) {
            if (!self::isValidProperty($property)) {
                continue;
            }

            $metadata->addProperty(
                property: $this->getPropertyMetadataForContext(
                    metadata: $metadata->findProperty($property->getName())
                        ?? new PropertyMetadata($property->getName()),
                    class: $class,
                    property: $property,
                    types: $types,
                ),
            );
        }

        return $metadata;
    }

    /**
     * @param \ReflectionClass<object> $class
     *
     * @throws ReaderExceptionInterface
     */
    private function getPropertyMetadataForContext(
        PropertyMetadata $metadata,
        \ReflectionClass $class,
        \ReflectionProperty $property,
        RepositoryInterface $types,
    ): PropertyMetadata {
        if ($property->hasDefaultValue()) {
            $metadata->setDefaultValue($property->getDefaultValue());
        }

        if ($property->isPromoted()) {
            $parameter = $this->findParameter($class, $property);

            if ($parameter?->isDefaultValueAvailable() === true) {
                $metadata->setDefaultValue($parameter->getDefaultValue());
            }
        }

        $statement = $this->properties->findPropertyType($property);

        if ($statement !== null) {
            try {
                $metadata->setType($types->get($statement));
            } catch (TypeNotFoundException) {
            }
        }

        return $metadata;
    }

    /**
     * @param \ReflectionClass<object> $class
     */
    private function findParameter(\ReflectionClass $class, \ReflectionProperty $property): ?\ReflectionParameter
    {
        $constructor = $class->getConstructor();

        if ($constructor === null) {
            return null;
        }

        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->getName() === $property->getName()) {
                return $parameter;
            }
        }

        return null;
    }

    private static function isValidProperty(\ReflectionProperty $property): bool
    {
        return !$property->isStatic()
            && $property->isPublic();
    }
}
