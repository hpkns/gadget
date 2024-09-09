<?php

namespace Hpkns\tests;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Iban;

class ObjectCreationTest extends TestCase
{
    public function test_simple_object_creation()
    {
        $iban = Iban::build(['value' => $value = 'BE35456459965337']);

        $this->assertInstanceOf(Iban::class, $iban);
        $this->assertEquals($iban->value, $value);
    }
}