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

use Eloquent\Liberator\Test\Fixture\Object;
use Eloquent\Liberator\Test\TestCase;

class LiberatorClassTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->class = 'Eloquent\Liberator\Test\Fixture\Object';
        $this->proxy = new LiberatorClass($this->class);
    }

    public function fixtureData()
    {
        $data = array();

        $class = 'Eloquent\Liberator\Test\Fixture\Object';
        $proxy = new LiberatorClass($class);
        $data['Class with no inheritance'] = array($class, $proxy);

        $class = 'Eloquent\Liberator\Test\Fixture\ChildObject';
        $proxy = new LiberatorClass($class);
        $data['Child class'] = array($class, $proxy);

        return $data;
    }

    /**
     * @dataProvider fixtureData
     */
    public function testRecursive($class)
    {
        $recursiveProxy = new LiberatorClass($class, true);

        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy->staticObject());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy->staticObject()->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy->staticObject()->arrayValue());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy->staticObject()->string());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy->staticArray());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy->staticString());
    }

    /**
     * @dataProvider fixtureData
     */
    public function testCall($class, LiberatorClass $proxy)
    {
        $this->assertLiberatorCall($proxy, 'staticPublicMethod', array('foo', 'bar'));
        $this->assertLiberatorCall($proxy, 'staticProtectedMethod', array('foo', 'bar'));
        $this->assertLiberatorCall($proxy, 'staticPrivateMethod', array('foo', 'bar'));
        $this->assertLiberatorCall($proxy, 'foo', array('bar', 'baz'), true);
    }

    public function testCallByReference()
    {
        $variable = null;
        $arguments = array(&$variable, 'foo');
        $this->proxy->liberatorCall('staticByReference', $arguments);

        $this->assertSame('foo', $variable);
    }

    public function testCallByReferenceStatic()
    {
        $class = LiberatorClass::popsGenerateStaticClassProxy('Eloquent\Liberator\Test\Fixture\Object');
        $variable = null;
        $arguments = array(&$variable, 'foo');
        $class::liberator()->liberatorCall('staticByReference', $arguments);

        $this->assertSame('foo', $variable);
    }

    /**
     * @dataProvider fixtureData
     */
    public function testSetGet($class, LiberatorClass $proxy)
    {
        $this->assertTrue(isset($proxy->staticPublicProperty));
        $this->assertTrue(isset($proxy->staticProtectedProperty));
        $this->assertTrue(isset($proxy->staticPrivateProperty));
        $this->assertEquals('staticPublicProperty', $proxy->staticPublicProperty);
        $this->assertEquals('staticProtectedProperty', $proxy->staticProtectedProperty);
        $this->assertEquals('staticPrivateProperty', $proxy->staticPrivateProperty);

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
        $this->assertEquals('staticPublicProperty', $proxy->staticPublicProperty);
        $this->assertEquals('staticProtectedProperty', $proxy->staticProtectedProperty);
        $this->assertEquals('staticPrivateProperty', $proxy->staticPrivateProperty);

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
            'Access to undeclared static property: Eloquent\Liberator\Test\Fixture\Object::$' . $arguments[0]
        );
        call_user_func_array(array($this->proxy, $method), $arguments);
    }

    public function testPopsGenerateStaticClassProxy()
    {
        $class = LiberatorClass::popsGenerateStaticClassProxy('Eloquent\Liberator\Test\Fixture\Object');

        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(is_subclass_of($class, 'Eloquent\Liberator\LiberatorClass'));

        $expected = new $class('Eloquent\Liberator\Test\Fixture\Object');
        $proxy = $class::liberator();

        $this->assertEquals($expected, $proxy);

        // recursive tests
        $class = LiberatorClass::popsGenerateStaticClassProxy('Eloquent\Liberator\Test\Fixture\Object', true);

        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $class::staticObject());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $class::staticObject()->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $class::staticObject()->arrayValue());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $class::staticObject()->string());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $class::staticArray());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $class::staticString());
    }
}
