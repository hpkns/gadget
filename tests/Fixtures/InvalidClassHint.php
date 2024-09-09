<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Creatable;

class InvalidClassHint
{
    use Creatable;

    public function __construct(
        readonly public NotARealClass $value,
    )
    {
        //
    }
}