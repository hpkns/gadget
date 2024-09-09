<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Creatable;

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