<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Buildable;

class InvalidClassHint
{
    use Buildable;

    public function __construct(
        readonly public NotARealClass $value,
    )
    {
        //
    }
}