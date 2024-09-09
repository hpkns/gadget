<?php

namespace Hpkns\Objkit;

trait Creatable
{
    /**
     *
     */
    public static function build(array $attributes): static
    {
        return ObjectBuilder::build(static::class, $attributes);
    }
}