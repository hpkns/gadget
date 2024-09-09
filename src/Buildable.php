<?php

namespace Hpkns\Objkit;

trait Buildable
{
    /**
     *
     */
    public static function build(array $attributes): static
    {
        return ObjectBuilder::build(static::class, $attributes);
    }
}