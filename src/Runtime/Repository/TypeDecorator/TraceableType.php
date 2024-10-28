<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Runtime\Repository\TypeDecorator;

use TypeLang\Mapper\Runtime\Context;
use TypeLang\Mapper\Runtime\Context\Direction;
use TypeLang\Mapper\Runtime\Path\PathInterface;
use TypeLang\Mapper\Runtime\Tracing\TracerInterface;
use TypeLang\Mapper\Type\TypeInterface;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\Mapper\Runtime\Repository
 */
final class TraceableType extends TypeDecorator
{
    /**
     * @var non-empty-string
     */
    private readonly string $name;

    public function __construct(
        private readonly string $definition,
        private readonly TracerInterface $tracer,
        TypeInterface $delegate,
    ) {
        parent::__construct($delegate);

        $inner = $this->getDecoratedType();

        $this->name = \vsprintf('"%s" using %s#%d', [
            \addcslashes($this->definition, '"'),
            $inner::class,
            \spl_object_id($inner),
        ]);
    }

    private static function getPath(Context $context): PathInterface
    {
        return $context->getPath();
    }

    private static function getDirection(Context $context): Context\DirectionInterface
    {
        if ($context->isNormalization()) {
            return Direction::Normalize;
        }

        return Direction::Denormalize;
    }

    public function match(mixed $value, Context $context): bool
    {
        $span = $this->tracer->start(\sprintf('Match %s', $this->name));

        try {
            $span->setAttribute('value', $value);
            $span->setAttribute('direction', self::getDirection($context));
            $span->setAttribute('path', self::getPath($context));

            $result = parent::match($value, $context);

            $span->setAttribute('result', $result);

            return $result;
        } finally {
            $span->stop();
        }
    }

    public function cast(mixed $value, Context $context): mixed
    {
        $span = $this->tracer->start(\sprintf('Cast %s', $this->name));

        try {
            $span->setAttribute('value', $value);
            $span->setAttribute('direction', self::getDirection($context));
            $span->setAttribute('path', self::getPath($context));

            $result = parent::cast($value, $context);

            $span->setAttribute('result', $result);

            return $result;
        } finally {
            $span->stop();
        }
    }
}
