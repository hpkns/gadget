<?php

namespace Tests\Fixtures;

use Hpkns\Gadget\Creatable;

class Address
{
    use Creatable;

    public function __construct(
        readonly public array       $streetAddress,
        readonly public string      $postalCode,
        readonly public string      $city,
        readonly public Country     $country,
        readonly public AddressType $type = AddressType::Business
    )
    {
        //
    }
}