<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Liberator;

use Eloquent\Pops\Exception\InvalidTypeException;
use Eloquent\Pops\ProxyObject;
use ReflectionObject;

/**
 * An object proxy that circumvents access modifier restrictions.
 */
class LiberatorObject extends ProxyObject implements LiberatorProxyInterface
{
    /**
     * Call a method on the wrapped object with support for by-reference
     * arguments.
     *
     * @param string $method     The name of the method to call.
     * @param array  &$arguments The arguments.
     *
     * @return mixed The result of the method call.
     */
    public function liberatorCall($method, array &$arguments)
    {
        return $this->popsCall($method, $arguments);
    }

    /**
     * Set the wrapped object.
     *
     * @param string $object The object to wrap.
     *
     * @throws InvalidTypeException If the supplied value is not the correct type.
     */
    public function setPopsValue($object)
    {
        parent::setPopsValue($object);

        $this->liberatorReflector = new ReflectionObject($object);
    }

    /**
     * Call a method on the wrapped object with support for by-reference
     * arguments.
     *
     * @param string $method     The name of the method to call.
     * @param array  &$arguments The arguments.
     *
     * @return mixed The result of the method call.
     */
    public function popsCall($method, array &$arguments)
    {
        if ($this->liberatorReflector->hasMethod($method)) {
            $method = $this->liberatorReflector->getMethod($method);
            $method->setAccessible(true);

            return $this->popsProxySubValue(
                $method->invokeArgs($this->popsValue(), $arguments)
            );
        }

        return parent::popsCall($method, $arguments);
    }

    /**
     * Set the value of a property on the wrapped object.
     *
     * @param string $property The property name.
     * @param mixed  $value    The new value.
     */
    public function __set($property, $value)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            $propertyReflector->setValue($this->popsValue(), $value);

            return;
        }

        parent::__set($property, $value);
    }

    /**
     * Get the value of a property from the wrapped object.
     *
     * @param string $property The property name.
     *
     * @return mixed The property value.
     */
    public function __get($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            return $this->popsProxySubValue(
                $propertyReflector->getValue($this->popsValue())
            );
        }

        return parent::__get($property);
    }

    /**
     * Returns true if the property exists on the wrapped object.
     *
     * @param string $property The name of the property to search for.
     *
     * @return boolean True if the property exists.
     */
    public function __isset($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            return null !== $propertyReflector->getValue($this->popsValue());
        }

        return parent::__isset($property);
    }

    /**
     * Unset a property from the wrapped object.
     *
     * @param string $property The property name.
     */
    public function __unset($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            $propertyReflector->setValue($this->popsValue(), null);

            return;
        }

        parent::__unset($property);
    }

    /**
     * Get the proxy class.
     *
     * @return string The proxy class.
     */
    protected static function popsProxyClass()
    {
        return 'Eloquent\Liberator\Liberator';
    }

    /**
     * Get the class reflector.
     *
     * @return ReflectionObject The class reflector.
     */
    protected function liberatorReflector()
    {
        return $this->liberatorReflector;
    }

    /**
     * Get a property reflector.
     *
     * @param string $property The property name.
     *
     * @return ReflectionProperty|null The property reflector, or null if no such property exists.
     */
    protected function liberatorPropertyReflector($property)
    {
        $classReflector = $this->liberatorReflector();

        while ($classReflector) {
            if ($classReflector->hasProperty($property)) {
                $propertyReflector = $classReflector->getProperty($property);
                $propertyReflector->setAccessible(true);

                return $propertyReflector;
            }

            $classReflector = $classReflector->getParentClass();
        }

        return null;
    }

    private $liberatorReflector;
}
