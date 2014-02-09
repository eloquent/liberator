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
use Eloquent\Pops\Proxy;
use Eloquent\Pops\ProxyClassInterface;

/**
 * A proxy that circumvents access modifier restrictions.
 */
class Liberator extends Proxy
{
    /**
     * Wrap the supplied value in a liberator proxy.
     *
     * @param mixed        $value       The value to wrap.
     * @param boolean|null $isRecursive True if the value should be recursively proxied.
     *
     * @return LiberatorProxyInterface The proxied value.
     */
    public static function liberate($value, $isRecursive = null)
    {
        return static::proxy($value, $isRecursive);
    }

    /**
     * Wrap the supplied class in a non-static liberator proxy.
     *
     * @param string       $class       The name of the class to wrap.
     * @param boolean|null $isRecursive True if the class should be recursively proxied.
     *
     * @return ProxyClassInterface  The non-static class proxy.
     * @throws InvalidTypeException If the supplied value is not the correct type.
     */
    public static function liberateClass($class, $isRecursive = null)
    {
        return static::proxyClass($class, $isRecursive);
    }

    /**
     * Wrap the supplied class in a static liberator proxy.
     *
     * @param string       $class       The name of the class to wrap.
     * @param boolean|null $isRecursive True if the class should be recursively proxied.
     * @param string|null  $proxyClass  The class name to use for the proxy class.
     *
     * @return string The static class proxy.
     */
    public static function liberateClassStatic(
        $class,
        $isRecursive = null,
        $proxyClass = null
    ) {
        return static::proxyClassStatic($class, $isRecursive, $proxyClass);
    }

    /**
     * Get the array proxy class.
     *
     * @return string The array proxy class.
     */
    protected static function proxyArrayClass()
    {
        return 'Eloquent\Liberator\LiberatorArray';
    }

    /**
     * Get the class proxy class.
     *
     * @return string The class proxy class.
     */
    protected static function proxyClassClass()
    {
        return 'Eloquent\Liberator\LiberatorClass';
    }

    /**
     * Get the object proxy class.
     *
     * @return string The object proxy class.
     */
    protected static function proxyObjectClass()
    {
        return 'Eloquent\Liberator\LiberatorObject';
    }
}
