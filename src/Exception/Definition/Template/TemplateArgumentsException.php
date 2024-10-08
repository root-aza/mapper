<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Exception\Definition\Template;

use TypeLang\Mapper\Exception\Definition\DefinitionException;

/**
 * An exception associated with ALL possible template arguments.
 */
abstract class TemplateArgumentsException extends DefinitionException
{
    /**
     * @var int
     */
    protected const CODE_ERROR_LAST = parent::CODE_ERROR_LAST;
}