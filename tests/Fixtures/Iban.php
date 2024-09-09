<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Attributes\ArrayOf;
use Hpkns\Objkit\Creatable;

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