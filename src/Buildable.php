<?php

namespace Hpkns\Objkit;

use Hpkns\Objkit\Exceptions\ObjectCreationException;

trait Buildable
{
    /**
     * Create an instance of the object using the object builder.
     *
     * @throws ObjectCreationException
     */
    public static function build(array $attributes): static
    {
        return ObjectBuilder::build(static::class, $attributes);
    }
}