<?php

namespace Tests\Fixtures;

use ArrayAccess;
use Hpkns\Objkit\Creatable;
use JsonSerializable;

class Unbuildable
{
    use Creatable;

    public function __construct(
        public readonly int|string                   $value,
        public readonly JsonSerializable&ArrayAccess $other
    )
    {
        //
    }
}