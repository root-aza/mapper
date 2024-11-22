<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Exception\Definition\Template;

use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Occurs when a type supports fewer arguments than were passed
 */
class TooManyTemplateArgumentsException extends TemplateArgumentsCountException
{
    /**
     * @param int<0, max> $minSupportedArgumentsCount
     * @param int<0, max> $maxSupportedArgumentsCount
     */
    public static function becauseTemplateArgumentsRangeOverflows(
        int $minSupportedArgumentsCount,
        int $maxSupportedArgumentsCount,
        NamedTypeNode $type,
        ?\Throwable $previous = null,
    ): self {
        $template = 'Type "{{type}}" only accepts %s template argument(s), '
            . 'but {{passedArgumentsCount}} were passed';

        $template = $minSupportedArgumentsCount === $maxSupportedArgumentsCount
            ? \sprintf($template, '{{minSupportedArgumentsCount}}')
            : \sprintf($template, 'from {{minSupportedArgumentsCount}} to {{maxSupportedArgumentsCount}}');

        return new self(
            passedArgumentsCount: $type->arguments?->count() ?? 0,
            minSupportedArgumentsCount: $minSupportedArgumentsCount,
            maxSupportedArgumentsCount: $maxSupportedArgumentsCount,
            type: $type,
            template: $template,
            previous: $previous,
        );
    }
}
