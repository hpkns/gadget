<?php

namespace Tests\Fixtures;

use Hpkns\Gadget\Attributes\ArrayOf;
use Hpkns\Gadget\Creatable;

class Iban
{
    use Creatable;

    public function __construct(
        public readonly string $value,
        #[ArrayOf('string')]
        public readonly array $accounts = [],
    )
    {
        //
    }
}