<?php

namespace Tests\Fixtures;

use ArrayAccess;
use Hpkns\Objkit\Buildable;
use JsonSerializable;

class Unbuildable
{
    use Buildable;

    public function __construct(
        public readonly int|string                   $value,
    )
    {
        //
    }
}