<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Attributes\ArrayOf;
use Hpkns\Objkit\Buildable;

class Person
{
    use Buildable;

    public function __construct(
        readonly public string   $givenName,
        readonly public string   $familyName,
        readonly public ?Address $address = null,
        #[ArrayOf(Person::class)]
        readonly public array    $parents = [],
    )
    {
        //
    }
}