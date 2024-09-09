<?php

namespace Hpkns\Objkit\Attributes;

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