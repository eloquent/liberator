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

use Eloquent\Pops\Pops;

class Liberator extends Pops
{
    /**
     * @param mixed $value
     * @param boolean $recursive
     *
     * @return LiberatorProxy
     */
    public static function liberate($value, $recursive = null)
    {
        return static::proxy($value, $recursive);
    }

    /**
     * @param string $class
     * @param boolean $recursive
     *
     * @return LiberatorClass
     */
    public static function liberateClass($class, $recursive = null)
    {
        return static::proxyClass($class, $recursive);
    }

    /**
     * @param string $class
     * @param boolean $recursive
     * @param string $proxyClass
     *
     * @return string
     */
    public static function liberateClassStatic(
        $class,
        $recursive = null,
        $proxyClass = null
    ) {
        return static::proxyClassStatic($class, $recursive, $proxyClass);
    }

    /**
     * @return string
     */
    protected static function proxyArrayClass()
    {
        return __NAMESPACE__.'\LiberatorArray';
    }

    /**
     * @return string
     */
    protected static function proxyClassClass()
    {
        return __NAMESPACE__.'\LiberatorClass';
    }

    /**
     * @return string
     */
    protected static function proxyObjectClass()
    {
        return __NAMESPACE__.'\LiberatorObject';
    }
}
