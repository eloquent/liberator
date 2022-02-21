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

class Obj
{
    public static function staticPublicMethod()
    {
        return [__FUNCTION__, func_get_args()];
    }

    protected static function staticProtectedMethod()
    {
        return [__FUNCTION__, func_get_args()];
    }

    private static function staticPrivateMethod()
    {
        return [__FUNCTION__, func_get_args()];
    }

    public static function __callStatic($name, array $arguments)
    {
        return [__FUNCTION__, func_get_args()];
    }

    public static function staticObject()
    {
        return new static();
    }

    public static function staticArray()
    {
        return [];
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
        return [__FUNCTION__, func_get_args()];
    }

    protected function protectedMethod()
    {
        return [__FUNCTION__, func_get_args()];
    }

    private function privateMethod()
    {
        return [__FUNCTION__, func_get_args()];
    }

    public function __call($method, array $arguments)
    {
        return [__FUNCTION__, func_get_args()];
    }

    public function object()
    {
        return new static();
    }

    public function arrayValue()
    {
        return [];
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
