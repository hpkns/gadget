<?php

namespace Tests\Fixtures;

use Hpkns\Gadget\Creatable;

class ListItem
{
    use Creatable;

    public function __construct(
        readonly public ?self $previous,
        public                $value,
    )
    {
        //
    }
}