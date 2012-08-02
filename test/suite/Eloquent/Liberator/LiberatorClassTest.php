<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liberator;

use Eloquent\Liberator\Test\Fixture\Object;
use Eloquent\Liberator\Test\TestCase;

class LiberatorClassTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_class = __NAMESPACE__.'\Test\Fixture\Object';
        $this->_proxy = new LiberatorClass($this->_class);
    }

    public function fixtureData()
    {
        $data = array();

        // #0: class with no inheritance
        $class = __NAMESPACE__.'\Test\Fixture\Object';
        $proxy = new LiberatorClass($class);
        $data[] = array($class, $proxy);

        // #1: child class
        $class = __NAMESPACE__.'\Test\Fixture\ChildObject';
        $proxy = new LiberatorClass($class);
        $data[] = array($class, $proxy);

        return $data;
    }

    /**
     * @dataProvider fixtureData
     */
    public function testRecursive($class)
    {
        $recursiveProxy = new LiberatorClass($class, true);

        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $recursiveProxy->staticObject()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $recursiveProxy->staticObject()->object()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $recursiveProxy->staticObject()->arrayValue()
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $recursiveProxy->staticObject()->string()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $recursiveProxy->staticArray()
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $recursiveProxy->staticString()
        );
    }

    /**
     * @dataProvider fixtureData
     */
    public function testCall($class, LiberatorClass $proxy)
    {
        $this->assertLiberatorCall(
            $proxy,
            'staticPublicMethod',
            array('foo', 'bar')
        );
        $this->assertLiberatorCall(
            $proxy,
            'staticProtectedMethod',
            array('foo', 'bar')
        );
        $this->assertLiberatorCall(
            $proxy,
            'staticPrivateMethod',
            array('foo', 'bar')
        );
        $this->assertLiberatorCall(
            $proxy,
            'foo',
            array('bar', 'baz'),
            true
        );
    }

    /**
     * @dataProvider fixtureData
     */
    public function testSetGet($class, LiberatorClass $proxy)
    {
        $this->assertTrue(isset($proxy->staticPublicProperty));
        $this->assertTrue(isset($proxy->staticProtectedProperty));
        $this->assertTrue(isset($proxy->staticPrivateProperty));
        $this->assertEquals(
            'staticPublicProperty',
            $proxy->staticPublicProperty
        );
        $this->assertEquals(
            'staticProtectedProperty',
            $proxy->staticProtectedProperty
        );
        $this->assertEquals(
            'staticPrivateProperty',
            $proxy->staticPrivateProperty
        );

        $proxy->staticPublicProperty = 'foo';
        $proxy->staticProtectedProperty = 'bar';
        $proxy->staticPrivateProperty = 'baz';

        $this->assertTrue(isset($proxy->staticPublicProperty));
        $this->assertTrue(isset($proxy->staticProtectedProperty));
        $this->assertTrue(isset($proxy->staticPrivateProperty));
        $this->assertEquals('foo', $proxy->staticPublicProperty);
        $this->assertEquals('bar', $proxy->staticProtectedProperty);
        $this->assertEquals('baz', $proxy->staticPrivateProperty);

        unset($proxy->staticPublicProperty);
        unset($proxy->staticProtectedProperty);
        unset($proxy->staticPrivateProperty);

        $this->assertFalse(isset($proxy->staticPublicProperty));
        $this->assertFalse(isset($proxy->staticProtectedProperty));
        $this->assertFalse(isset($proxy->staticPrivateProperty));

        $proxy->staticPublicProperty = 'staticPublicProperty';
        $proxy->staticProtectedProperty = 'staticProtectedProperty';
        $proxy->staticPrivateProperty = 'staticPrivateProperty';

        $this->assertTrue(isset($proxy->staticPublicProperty));
        $this->assertTrue(isset($proxy->staticProtectedProperty));
        $this->assertTrue(isset($proxy->staticPrivateProperty));
        $this->assertEquals(
            'staticPublicProperty',
            $proxy->staticPublicProperty
        );
        $this->assertEquals(
            'staticProtectedProperty',
            $proxy->staticProtectedProperty
        );
        $this->assertEquals(
            'staticPrivateProperty',
            $proxy->staticPrivateProperty
        );

        $this->assertFalse(isset($proxy->foo));
    }

    public function setGetFailureData()
    {
        return array(
            array('__set', array('foo', 'bar')),
            array('__get', array('foo')),
            array('__unset', array('foo')),
        );
    }

    /**
     * @dataProvider setGetFailureData
     */
    public function testSetGetFailure($method, array $arguments)
    {
        $this->setExpectedException(
            'LogicException',
            'Access to undeclared static property: '.
                __NAMESPACE__.'\Test\Fixture\Object::$'.
                $arguments[0]
        );
        call_user_func_array(array($this->_proxy, $method), $arguments);
    }

    public function testPopsGenerateStaticClassProxy()
    {
        $class = LiberatorClass::popsGenerateStaticClassProxy(
            __NAMESPACE__.'\Test\Fixture\Object'
        );

        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(
            is_subclass_of($class, __NAMESPACE__.'\LiberatorClass')
        );

        $expected = new $class(__NAMESPACE__.'\Test\Fixture\Object');
        $proxy = $class::liberator();

        $this->assertEquals($expected, $proxy);

        // recursive tests
        $class = LiberatorClass::popsGenerateStaticClassProxy(
            __NAMESPACE__.'\Test\Fixture\Object',
            true
        );

        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $class::staticObject()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $class::staticObject()->object()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $class::staticObject()->arrayValue()
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $class::staticObject()->string()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $class::staticArray()
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $class::staticString()
        );
    }

    public function testByReference()
    {
        $variable = null;
        $arguments = array(&$variable, 'foo');
        $this->_proxy->liberatorCall('staticByReference', $arguments);

        $this->assertSame('foo', $variable);
    }

    public function testByReferenceStatic()
    {
        $class = LiberatorClass::popsGenerateStaticClassProxy(
            __NAMESPACE__.'\Test\Fixture\Object'
        );
        $variable = null;
        $arguments = array(&$variable, 'foo');
        $class::liberator()->liberatorCall('staticByReference', $arguments);

        $this->assertSame('foo', $variable);
    }
}
