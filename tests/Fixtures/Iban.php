<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Attributes\ArrayOf;
use Hpkns\Objkit\Buildable;

class Iban
{
    use Buildable;

    public function __construct(
        public readonly string $value,
        #[ArrayOf('string')]
        public readonly array $accounts = [],
    )
    {
        //
    }
}