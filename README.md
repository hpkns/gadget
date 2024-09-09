Gadget
===

Create data objects from unstructured data

# Installation

Simply get it from composer:

```shell
composer require hpkns/gadget
```

# Usage
```php
use Hpkns\Gadget\Attributes\ArrayOf;
use Hpkns\Gadget\Creatable;
use Hpkns\Gadget\ObjectBuilder;

class Person {
    use Creatable;
    
    public function __construct(
        readonly string $name,
        readonly int    $age,
        #[ArrayOf(Person)]
        readonly array  $parents = [],
    ) {
        //
    }
}

$jedi = Person::build([
    'name' => 'Luke',
    'age' => '19',
    'parents' => [
        [
            'name' => 'Anakin', 
            'age' => 41,
        ]
    ]
]);
```

