<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Liberator\Test\Fixture;

class Object
{
    public static function staticPublicMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    protected static function staticProtectedMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    private static function staticPrivateMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    public static function __callStatic($name, array $arguments)
    {
        return array(__FUNCTION__, func_get_args());
    }

    public static function staticObject()
    {
        return new static;
    }

    public static function staticArray()
    {
        return array();
    }

    public static function staticString()
    {
        return 'string';
    }

    public static function staticByReference(&$variable, $value)
    {
        $variable = $value;
    }

    public function publicMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    protected function protectedMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    private function privateMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    public function __call($method, array $arguments)
    {
        return array(__FUNCTION__, func_get_args());
    }

    public function object()
    {
        return new static;
    }

    public function arrayValue()
    {
        return array();
    }

    public function string()
    {
        return 'string';
    }

    public function __toString()
    {
        return 'foo';
    }

    public function byReference(&$variable, $value)
    {
        $variable = $value;
    }

    public static $staticPublicProperty = 'staticPublicProperty';
    protected static $staticProtectedProperty = 'staticProtectedProperty';
    private static $staticPrivateProperty = 'staticPrivateProperty';

    public $publicProperty = 'publicProperty';
    protected $protectedProperty = 'protectedProperty';
    private $privateProperty = 'privateProperty';
}
