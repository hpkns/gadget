<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Buildable;
use JsonSerializable;

class WithUnionParam
{
    use Buildable;

    public function __construct(
        public readonly JsonSerializable|int|string $value,
    )
    {
        //
    }
}