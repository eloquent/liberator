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

use Eloquent\Pops\ProxyClass;
use LogicException;
use ReflectionClass;

class LiberatorClass extends ProxyClass
{
    /**
     * @return LiberatorClass
     */
    public static function liberator()
    {
        return static::popsProxy();
    }

    /**
     * @param string $class
     * @param boolean $recursive
     */
    public function __construct($class, $recursive = null)
    {
        parent::__construct($class, $recursive);

        $this->liberatorReflector = new ReflectionClass($class);
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

            return static::popsProxySubValue(
                $method->invokeArgs(null, $arguments),
                $this->popsRecursive
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
            $propertyReflector->setValue(null, $value);

            return;
        }

        throw new LogicException(
            'Access to undeclared static property: '.
            $this->popsClass.
            '::$'.
            $property
        );
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            return static::popsProxySubValue(
                $propertyReflector->getValue(null),
                $this->popsRecursive
            );
        }

        throw new LogicException(
            'Access to undeclared static property: '.
            $this->popsClass.
            '::$'.
            $property
        );
    }

    /**
     * @param string $property
     *
     * @return boolean
     */
    public function __isset($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            return null !== $propertyReflector->getValue(null);
        }

        return parent::__isset($property);
    }

    /**
     * @param string $property
     */
    public function __unset($property)
    {
        if ($propertyReflector = $this->liberatorPropertyReflector($property)) {
            $propertyReflector->setValue(null, null);

            return;
        }

        throw new LogicException(
            'Access to undeclared static property: '.
            $this->popsClass.
            '::$'.
            $property
        );
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
     * @var ReflectionClass
     */
    protected $liberatorReflector;
}
