<?php

declare(strict_types=1);

namespace TypeLang\Mapper\Runtime;

use Psr\Log\LoggerInterface;
use TypeLang\Mapper\Runtime\Tracing\TracerInterface;

interface ConfigurationInterface
{
    /**
     * Specifies the default normalization settings for the object.
     *
     * In case of the method returns {@see true}, the object will be converted
     * to an associative array (hash map) unless otherwise specified.
     */
    public function isObjectsAsArrays(): bool;

    /**
     * In case of returns {@see true}, then all composite types will
     * be displayed along with detailed fields/values.
     *
     * Otherwise, only a short description will be displayed.
     */
    public function isDetailedTypes(): bool;

    /**
     * In case of method returns {@see true}, all types will be checked
     * for compliance.
     *
     * Otherwise, the value will attempt to be converted to the
     * required type if possible.
     */
    public function isStrictTypesEnabled(): bool;

    /**
     * If this method returns {@see LoggerInterface}, then the given logger
     * will be enabled. Otherwise logger should be disabled.
     */
    public function getLogger(): ?LoggerInterface;

    /**
     * If this method returns {@see TracerInterface}, then the application
     * tracing will be enabled. Otherwise tracing should be disabled.
     */
    public function getTracer(): ?TracerInterface;
}
