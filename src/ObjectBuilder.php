<?php

namespace Hpkns\Objkit;

use BackedEnum;
use Hpkns\Objkit\Attributes\ArrayOf;
use Hpkns\Objkit\Exceptions\ClassDoesNotExistException;
use Hpkns\Objkit\Exceptions\InvalidEnumValueException;
use Hpkns\Objkit\Exceptions\MissingParameterException;
use Hpkns\Objkit\Exceptions\NotImplementedException;
use Hpkns\Objkit\Exceptions\ObjectCreationException;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use UnitEnum;

/**
 * @template T
 */
class ObjectBuilder
{
    /**
     * The class for which we are building an instance.
     *
     * @var ReflectionClass<T>
     */
    protected ReflectionClass $class;

    /**
     * The constructor of the class being considered.
     */
    protected ?ReflectionMethod $constructor;

    /**
     * Types treated as native (that we don't try to instance)
     */
    protected array $nativeTypes = [
        'array', 'bool', 'callable', 'float', 'int', 'null', 'object', 'string', 'false', 'iterable', 'mixed', 'never', 'true', 'void',
    ];

    /**
     * @param object|class-string<T> $class
     * @throws ClassDoesNotExistException
     */
    public function __construct(object|string $class)
    {
        try {
            $this->class = new ReflectionClass($class);
            $this->constructor = $this->class->getConstructor();
        } catch (ReflectionException $e) {
            throw new ClassDoesNotExistException("Class {$class} does not exist.", $e->getCode(), $e);
        }
    }

    /**
     * Static helper for buildObject.
     *
     * @param class-string<T> $class
     * @param array           $parameters
     * @return T
     *
     * @throws ObjectCreationException
     * @see ObjectBuilder::buildObject()
     */
    public static function build(string $class, array $parameters): object
    {
        return (new static($class))->buildObject($parameters);
    }


    /**
     * Create an object of a given class based on non-structured properties.
     *
     * @param array $parameters
     * @return T
     *
     * @throws ObjectCreationException
     */
    protected function buildObject(array $parameters): object
    {
        $className = $this->class->getName();

        if ($this->constructor === null) {
            return new $className();
        }

        return new $className(...$this->getParameters($this->constructor, $parameters));
    }

    /**
     * Get the formatted parameters.
     *
     * @throws ObjectCreationException
     */
    protected function getParameters(ReflectionMethod $method, array $parameters = []): array
    {
        $formatted = [];

        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (!array_key_exists($name, $parameters)) {
                if ($parameter->isDefaultValueAvailable()) {
                    $formatted[$name] = $parameter->getDefaultValue();
                } else if ($parameter->allowsNull()) {
                    $formatted[$name] = null;
                } else {
                    throw new MissingParameterException("Cannot create object: parameter '{$name}' does not exist, does not have a default value and cannot be null.");
                }
            } else {
                $formatted[$name] = $this->formatParameterValue($parameter, $parameters[$name]);
            }
        }

        return $formatted;
    }

    /**
     * Try to coax $value to the type that fits the $parameters definition best.
     *
     * @throws ObjectCreationException
     */
    protected function formatParameterValue(ReflectionParameter $parameter, mixed $value): mixed
    {
        $type = $parameter->getType();

        if ($type instanceof ReflectionNamedType) {
            if ($type->getName() === 'array' && $hintedType = $this->getArrayTypeAttribute($parameter)) {
                return array_map(fn(mixed $v) => $this->formatValue($hintedType, $v), (array)$value);
            } else {
                return $this->formatValue($type, $value);
            }
        } elseif ($type instanceof ReflectionUnionType) {
            throw new NotImplementedException("Cannot create parameter {$parameter->getName()}: union types are not supported");
        } elseif ($type instanceof ReflectionIntersectionType) {
            throw new NotImplementedException("Cannot create parameter {$parameter->getName()}: intersection types are not supported");
        }

        return $value;
    }

    /**
     * @throws ObjectCreationException
     */
    protected function formatValue(ReflectionNamedType|string $type, mixed $value): mixed
    {
        if (is_string($type)) {
            return in_array($type, $this->nativeTypes)
                ? $value
                : $this->instantiate($type, $value);
        } else {
            if ($value === null && $type->allowsNull()) {
                return null;
            }

            return $type->isBuiltin()
                ? $value
                : $this->instantiate($type->getName(), $value);
        }
    }

    /**
     * @param class-string<T> $className
     * @param array           $value
     * @return T
     * @throws ObjectCreationException
     */
    protected function instantiate(string $className, mixed $value): object
    {
        if ($className === 'self' || $className === 'static') {
            $className = $this->class->getName();
        }

        if (!class_exists($className)) {
            throw new ClassDoesNotExistException("Class {$className} does not exist.");
        } else {
            $class = new ReflectionClass($className);
        }

        if ($value instanceof $className) {
            return $value;
        }

        if ($this->usesTrait($class, Creatable::class)) {
            return $className::build($value);
        }

        if ($class->isEnum()) {
            return $this->instantiateEnum($class->getName(), $value);
        }

        return new $className($value);
    }


    /**
     * @param class-string<UnitEnum> $name
     * @param mixed                  $value
     * @throws ObjectCreationException
     */
    protected function instantiateEnum(string $name, mixed $value): UnitEnum|BackedEnum
    {
        if (method_exists($name, 'tryFrom')) {
            /** @var class-string<BackedEnum> $name */
            $enumVal = $name::tryFrom($value);

            if ($enumVal !== null) {
                return $enumVal;
            } else {
                throw new InvalidEnumValueException("Backed enum {$name} does not not have a value '{$value}'.");
            }
        }

        foreach ($name::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }

        throw new InvalidEnumValueException("Enum {$name} does not not have a value '{$value}'.");
    }

    protected function getArrayTypeAttribute(ReflectionParameter $parameter): ?string
    {
        $attributes = $parameter->getAttributes(ArrayOf::class);

        foreach ($attributes as $attribute) {
            /** @var ArrayOf $arrayOf */
            $arrayOf = $attribute->newInstance();

            if ($arrayOf->type) {
                return $arrayOf->type;
            }
        }

        return null;
    }

    /**
     */
    protected function usesTrait(ReflectionClass $class, string $trait): bool
    {
        return array_key_exists($trait, $class->getTraits());
    }
}