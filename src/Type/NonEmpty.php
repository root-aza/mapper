<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Type;

use TypeLang\Mapper\Exception\Mapping\InvalidValueException;
use TypeLang\Mapper\Runtime\Context\LocalContext;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\Template\TemplateArgumentNode;
use TypeLang\Parser\Node\Stmt\Template\TemplateArgumentsListNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;

class NonEmpty implements TypeInterface
{
    /**
     * @var non-empty-string
     */
    public const DEFAULT_TYPE_NAME = 'non-empty';

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly TypeInterface $type,
        protected readonly string $name = self::DEFAULT_TYPE_NAME,
    ) {}

    public function getTypeStatement(LocalContext $context): TypeStatement
    {
        if (!$context->isDetailedTypes()) {
            return new NamedTypeNode($this->name);
        }

        return new NamedTypeNode($this->name, new TemplateArgumentsListNode([
            new TemplateArgumentNode($this->type->getTypeStatement($context)),
        ]));
    }

    protected function isEmpty(mixed $value): bool
    {
        return $value === '' || $value === [] || $value === null;
    }

    public function match(mixed $value, LocalContext $context): bool
    {
        return !$this->isEmpty($value)
            && $this->type->match($value, $context);
    }

    /**
     * @throws InvalidValueException
     */
    public function cast(mixed $value, LocalContext $context): mixed
    {
        if (!$this->isEmpty($value)) {
            return $this->type->cast($value, $context);
        }

        throw InvalidValueException::becauseInvalidValueGiven(
            value: $value,
            expected: $this->getTypeStatement($context),
            context: $context,
        );
    }
}
