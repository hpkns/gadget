<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Creatable;

class Address
{
    use Creatable;

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