<?php

namespace Hpkns\tests;

use DateTimeImmutable;
use Hpkns\Objkit\Exceptions\ParameterValueIsNotAnArray;
use Hpkns\Objkit\Exceptions\ClassDoesNotExistException;
use Hpkns\Objkit\Exceptions\InvalidEnumValueException;
use Hpkns\Objkit\ObjectBuilder;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Address;
use Tests\Fixtures\Date;
use Tests\Fixtures\EmptyClass;
use Tests\Fixtures\Iban;
use Tests\Fixtures\InvalidClassHint;
use Tests\Fixtures\ListItem;
use Tests\Fixtures\Person;
use Tests\Fixtures\WithUnionParam;

class ObjectCreationTest extends TestCase
{
    public function test_simple_object_creation()
    {
        $iban = Iban::build(['value' => $value = 'BE35456459965337']);

        $this->assertInstanceOf(Iban::class, $iban);
        $this->assertEquals($iban->value, $value);
    }

    public function test_build_array_function()
    {
        $people = Person::buildArray([
            [
                'givenName' => 'Luke',
                'familyName' => 'Skywalyer',
            ],
            [
                'givenName' => 'Anakin',
                'familyName' => 'Skywalyer',
            ]
        ]);

        $this->assertCount(2, $people);

        foreach ($people as $person) {
            $this->assertInstanceOf(Person::class, $person);
        }
    }

    public function test_classes_with_no_constructor_just_get_built()
    {
        $object = ObjectBuilder::build(EmptyClass::class, ['property' => 'value']);

        $this->assertInstanceOf(EmptyClass::class, $object);
    }

    public function test_create_create_with_type_hints()
    {
        $person = Person::build([
            'givenName' => 'Luke',
            'familyName' => 'Skywalker',
            'address' => [
                'streetAddress' => ['Tatooine'],
                'postCode' => null,
                'country' => 'BE',
                'type' => 'Private'
            ],
            'parents' => [
                [
                    'givenName' => 'Anakin',
                    'familyName' => 'Skywalker',
                    'address' => null,
                ]
            ]
        ]);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertCount(1, $person->parents);
        $this->assertInstanceOf(Person::class, $person->parents[0]);
    }

    public function test_map_array_of_scalars()
    {
        $iban = Iban::build(['value' => 'BE35456459965337', 'accounts' => ['account one']]);
        $this->assertCount(1, $iban->accounts);
    }

    public function test_map_to_simple_classes()
    {
        $date = Date::build(['value' => $dateString = '2015-06-12']);

        $this->assertInstanceOf(DateTimeImmutable::class, $date->value);
        $this->assertEquals($dateString, $date->value->format('Y-m-d'));
    }

    public function test_complex_class_types()
    {
        $first = ListItem::build(['value' => 4]);
        $second = ListItem::build(['previous' => $first, 'value' => 'some thing']);

        $this->assertEquals(4, $first->value);
        $this->assertEquals('some thing', $second->value);
        $this->assertEquals($first, $second->previous);
    }

    public function test_fail_if_class_does_not_exist()
    {
        $this->expectException(ClassDoesNotExistException::class);

        ObjectBuilder::build('not a class', []);
    }

    public function test_can_build_object_with_union_property()
    {
        $obj = WithUnionParam::build(['value' => 12]);

        $this->assertInstanceOf(WithUnionParam::class, $obj);
    }

    public function test_enum_casting_fails_if_value_does_not_exist()
    {
        $this->expectException(InvalidEnumValueException::class);
        $this->expectExceptionMessage("Backed enum Tests\Fixtures\Country does not not have a value 'RU'");

        Address::build(['streetAddress' => [], 'country' => 'RU']);
    }

    public function test_backed_enum_casting_fails_if_value_does_not_exist()
    {
        $this->expectException(InvalidEnumValueException::class);
        $this->expectExceptionMessage("Enum Tests\Fixtures\AddressType does not not have a value 'I don’t know'");

        Address::build(['streetAddress' => [], 'type' => 'I don’t know']);
    }

    public function test_build_fail_if_trying_to_instantiate_a_non_existing_class()
    {
        $this->expectException(ClassDoesNotExistException::class);

        InvalidClassHint::build(['value' => 'something']);
    }

    public function test_hinted_type_parameter_fails_if_value_is_not_an_array()
    {
        $this->expectException(ParameterValueIsNotAnArray::class);
        $this->expectExceptionMessage('Cannot create instance of Tests\Fixtures\Address for parameter address: expected array, got string');

        Person::build([
            'givenName' => 'Luke',
            'familyName' => 'Skywalker',
            'address' => 'potatoe',
        ]);
    }
}