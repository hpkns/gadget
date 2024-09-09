<?php

namespace Hpkns\Gadget;

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