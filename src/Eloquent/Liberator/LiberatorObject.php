<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liberator;

use Eloquent\Pops\ProxyObject;
use ReflectionObject;

class LiberatorObject extends ProxyObject
{
    /**
     * @param object $object
     * @param boolean $recursive
     */
    public function __construct($object, $recursive = null)
    {
        parent::__construct($object, $recursive);

        $this->liberatorReflector = new ReflectionObject($object);
    }

    /**
     * @param string $method
     * @param array &$arguments
     *
     * @return mixed
     */
    public function popsCall($method, array &$arguments)
    {
        if ($this->liberatorReflector->hasMethod($method)) {
            $method = $this->liberatorReflector->getMethod($method);
            $method->setAccessible(true);

            return $this->popsProxySubValue(
                $method->invokeArgs($this->popsObject, $arguments)
            );
        }

        return parent::popsCall($method, $arguments);
    }

    /**
     * @param string $method
     * @param array &$arguments
     *
     * @return mixed
     */
    public function liberatorCall($method, array &$arguments)
    {
        return $this->popsCall($method, $arguments);
    }

    /**
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            $propertyReflector->setValue($this->popsObject, $value);

            return;
        }

        parent::__set($property, $value);
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            return $this->popsProxySubValue(
                $propertyReflector->getValue($this->popsObject)
            );
        }

        return parent::__get($property);
    }

    /**
     * @param string $property
     *
     * @return boolean
     */
    public function __isset($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            return null !== $propertyReflector->getValue($this->popsObject);
        }

        return parent::__isset($property);
    }

    /**
     * @param string $property
     */
    public function __unset($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            $propertyReflector->setValue($this->popsObject, null);

            return;
        }

        parent::__unset($property);
    }

    /**
     * @return string
     */
    protected static function popsProxyClass()
    {
        return __NAMESPACE__.'\Liberator';
    }

    /**
     * @param string $property
     *
     * @return ReflectionProperty|null
     */
    protected function liberatorPropertyReflector($property)
    {
        $classReflector = $this->liberatorReflector;

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

    /**
     * @var ReflectionObject
     */
    protected $liberatorReflector;
}
