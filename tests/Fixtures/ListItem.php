<?php

namespace Tests\Fixtures;

use Hpkns\Objkit\Buildable;

class ListItem
{
    use Buildable;

    public function __construct(
        readonly public ?self $previous,
        public                $value,
    )
    {
        //
    }
}