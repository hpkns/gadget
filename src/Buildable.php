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

    /**
     * Helper to facilitate the creation of objects of Buildables.
     *
     * @return array<static>
     *
     * @throws ObjectCreationException
     */
    public static function buildArray(array $attributes): array
    {
        return ObjectBuilder::buildArray(static::class, $attributes);
    }
}