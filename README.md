OBJKIT
===

Create data objects from unstructured data

# Installation

Simply get it from composer:

```shell
composer require hpkns/gadget
```

# Usage
```php
use Hpkns\Objkit\Attributes\ArrayOf;
use Hpkns\Objkit\Creatable;
use Hpkns\Objkit\ObjectBuilder;

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

