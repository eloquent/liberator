<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liberator\Test\Fixture;

class Object
{
    static public function staticPublicMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    static protected function staticProtectedMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    static private function staticPrivateMethod()
    {
        return array(__FUNCTION__, func_get_args());
    }

    static public function __callStatic($name, array $arguments)
    {
        return array(__FUNCTION__, func_get_args());
    }

    static public function staticObject()
    {
        return new static;
    }

    static public function staticArray()
    {
        return array();
    }

    static public function staticString()
    {
        return 'string';
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

    static public $staticPublicProperty = 'staticPublicProperty';
    static protected $staticProtectedProperty = 'staticProtectedProperty';
    static private $staticPrivateProperty = 'staticPrivateProperty';

    public $publicProperty = 'publicProperty';
    protected $protectedProperty = 'protectedProperty';
    private $privateProperty = 'privateProperty';
}
