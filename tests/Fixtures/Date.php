<?php

namespace Tests\Fixtures;

use DateTimeImmutable;
use Hpkns\Gadget\Creatable;

class Date
{
    use Creatable;

    public function __construct(
        readonly public DateTimeImmutable $value,
    )
    {
        //
    }
}