<?php
declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerInterface;
use Closure;
use ReflectionClass;
use ReflectionException;

/**
 * PSR-11 Compliant Dependency Injection Container
 * 
 * Provides service registration, singleton management, and automatic resolution.
 */
final class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $instances = [];
    private array $singletons = [];

    /**
     * Register a service binding
     */
    public function bind(string $abstract, Closure|string $concrete = null, bool $shared = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];
    }

    /**
     * Register a singleton service
     */
    public function singleton(string $abstract, Closure|string $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Resolve a service from the container
     */
    public function get(string $id)
    {
        // Check if already instantiated (singleton)
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        // Check if binding exists
        if (isset($this->bindings[$id])) {
            return $this->resolveBinding($id);
        }

        // Try to auto-resolve
        return $this->resolve($id);
    }

    /**
     * Check if a service exists in the container
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]) || class_exists($id);
    }

    /**
     * Resolve a binding
     */
    private function resolveBinding(string $abstract): mixed
    {
        $concrete = $this->bindings[$abstract]['concrete'];
        $shared = $this->bindings[$abstract]['shared'];

        if ($concrete instanceof Closure) {
            $instance = $concrete($this);
        } else {
            $instance = $this->resolve($concrete);
        }

        if ($shared) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * Resolve a class with automatic dependency injection
     */
    private function resolve(string $class): mixed
    {
        try {
            $reflector = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new \RuntimeException("Class {$class} not found: " . $e->getMessage());
        }

        if (!$reflector->isInstantiable()) {
            throw new \RuntimeException("Class {$class} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = $this->resolveDependencies($constructor);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve constructor dependencies
     */
    private function resolveDependencies(\ReflectionMethod $constructor): array
    {
        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \RuntimeException("Cannot resolve parameter \${$parameter->name} in {$constructor->getDeclaringClass()->getName()}");
                }
            } elseif ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new \RuntimeException("Cannot resolve parameter \${$parameter->name} in {$constructor->getDeclaringClass()->getName()}");
            }
        }

        return $dependencies;
    }

    /**
     * Set an instance directly (useful for testing)
     */
    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    /**
     * Flush the container (useful for testing)
     */
    public function flush(): void
    {
        $this->bindings = [];
        $this->instances = [];
        $this->singletons = [];
    }
}
