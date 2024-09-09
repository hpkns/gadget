<?php

namespace Tests\Fixtures;

use DateTimeImmutable;
use Hpkns\Objkit\Buildable;

class Date
{
    use Buildable;

    public function __construct(
        readonly public DateTimeImmutable $value,
    )
    {
        //
    }
}