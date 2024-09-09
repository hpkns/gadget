<?php

namespace Tests\Fixtures;

use DateTimeImmutable;
use Hpkns\Objkit\Creatable;

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