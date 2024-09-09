<?php

namespace Tests\Fixtures;

use Hpkns\Gadget\Creatable;

class Iban
{
    use Creatable;

    public function __construct(
        public readonly string $value
    )
    {
        //
    }
}