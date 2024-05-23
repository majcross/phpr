<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container
{
    private array $definations = [];
    private array $resolved = [];

    public function addDefinations(array $newDefinations)
    {
        $this->definations = [...$this->definations, ...$newDefinations];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new ReflectionClass($className);


        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$className} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $className;
        }

        $params = $constructor->getParameters();
        if (count($params) === 0) {
            return new $className;
        }

        $dependencies = [];

        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // echo "Parameter name: $name\n";
            // echo "Parameter type: ";
            // dd($type);

            if (!$type) {
                throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
            }

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException("Failed to resolve class {$className} because invalid param name.");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);

        // echo "Resolving class: $className\n";

        // Attempt to instantiate the class
        // $instance = $reflectionClass->newInstance();

        // if ($instance === null) {
        //     throw new ContainerException("Failed to instantiate class {$className}");
        // }

        // return $instance;





        // foreach ($constructor->getParameters() as $parameter) {
        //     $type = $parameter->getType();

        //     if ($type !== null && !$type->isBuiltin()) {
        //         $dependencyClassName = $type->getName();
        //         $dependencies[] = $this->resolve($dependencyClassName);
        //     } else {
        //         // Handle the scenario where the type cannot be determined
        //         if ($parameter->getName() === 'basePath') {
        //             // Provide a default value for basePath
        //             $dependencies[] = '/path/to/default';
        //         } else {
        //             throw new ContainerException("Unable to determine type of parameter {$parameter->getName()} in $className");
        //         }
        //     }
        // }

        // return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $id)
    {
        if (!array_key_exists($id, $this->definations)) {
            throw new  ContainerException("Class {$id} does not exist in container.");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factory = $this->definations[$id];
        $dependency = $factory($this);

        $this->resolved[$id] = $dependency;

        return $dependency;
    }
}
