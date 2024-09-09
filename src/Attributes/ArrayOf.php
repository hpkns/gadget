<?php

namespace Hpkns\Gadget\Attributes;

#[\Attribute]
class ArrayOf
{
    public function __construct(
        readonly public string $type
    )
    {
        //
    }
}