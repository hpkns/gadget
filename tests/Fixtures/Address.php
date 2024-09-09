<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Buildable;

class Address
{
    use Buildable;

    public function __construct(
        readonly public array       $streetAddress,
        readonly public ?string     $postalCode = null,
        readonly public ?string     $city = null,
        readonly public ?Country    $country = null,
        readonly public AddressType $type = AddressType::Business
    )
    {
        //
    }
}