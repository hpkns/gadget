<?php

namespace Hpkns\Gadget;

/**
 * @template T
 */
class ObjectBuilder
{
    /**
     * Create an object of a given class based on non-structured properties.
     *
     * @param class-string<T> $class
     * @param array           $properties
     * @return T
     */
    public static function build(string $class, array $properties): object
    {
        return new $class(...$properties);
    }
}