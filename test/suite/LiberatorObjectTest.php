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

use Eloquent\Liberator\Test\Fixture\ChildObject;
use Eloquent\Liberator\Test\Fixture\Object;
use Eloquent\Liberator\Test\Fixture\Overload;
use Eloquent\Liberator\Test\TestCase;

class LiberatorObjectTest extends TestCase
{
    public function fixtureData()
    {
        $data = array();

        $object = new Object;
        $proxy = new LiberatorObject($object);
        $data['Object with no inheritance'] = array($object, $proxy);

        $object = new ChildObject;
        $proxy = new LiberatorObject($object);
        $data['Child object'] = array($object, $proxy);

        return $data;
    }

    /**
     * @dataProvider fixtureData
     */
    public function testRecursive(Object $object)
    {
        $recursiveProxy = new LiberatorObject($object, true);

        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy->object()->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy->object()->arrayValue());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy->object()->string());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy->arrayValue());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy->string());
    }

    /**
     * @dataProvider fixtureData
     */
    public function testCall(Object $object, LiberatorObject $proxy)
    {
        $this->assertLiberatorCall($proxy, 'publicMethod', array('foo', 'bar'));
        $this->assertLiberatorCall($proxy, 'protectedMethod', array('foo', 'bar'));
        $this->assertLiberatorCall($proxy, 'privateMethod', array('foo', 'bar'));
        $this->assertLiberatorCall($proxy, 'foo', array('bar', 'baz'), true);
    }

    /**
     * @dataProvider fixtureData
     */
    public function testCallByReference(Object $object, LiberatorObject $proxy)
    {
        $variable = null;
        $arguments = array(&$variable, 'foo');
        $proxy->liberatorCall('byReference', $arguments);

        $this->assertSame('foo', $variable);
    }

    /**
     * @dataProvider fixtureData
     */
    public function testSetGet(Object $object, LiberatorObject $proxy)
    {
        $this->assertTrue(isset($proxy->publicProperty));
        $this->assertTrue(isset($proxy->protectedProperty));
        $this->assertTrue(isset($proxy->privateProperty));
        $this->assertEquals('publicProperty', $proxy->publicProperty);
        $this->assertEquals('protectedProperty', $proxy->protectedProperty);
        $this->assertEquals('privateProperty', $proxy->privateProperty);

        $proxy->publicProperty = 'foo';
        $proxy->protectedProperty = 'bar';
        $proxy->privateProperty = 'baz';

        $this->assertTrue(isset($proxy->publicProperty));
        $this->assertTrue(isset($proxy->protectedProperty));
        $this->assertTrue(isset($proxy->privateProperty));
        $this->assertEquals('foo', $proxy->publicProperty);
        $this->assertEquals('bar', $proxy->protectedProperty);
        $this->assertEquals('baz', $proxy->privateProperty);

        unset($proxy->publicProperty);
        unset($proxy->protectedProperty);
        unset($proxy->privateProperty);

        $this->assertFalse(isset($proxy->publicProperty));
        $this->assertFalse(isset($proxy->protectedProperty));
        $this->assertFalse(isset($proxy->privateProperty));

        $proxy->foo = 'bar';

        $this->assertTrue(isset($proxy->foo));
        $this->assertTrue(isset($object->foo));
        $this->assertEquals('bar', $proxy->foo);
        $this->assertEquals('bar', $object->foo);

        $object = new Overload;
        $object->values = array('foo' => 'bar');
        $proxy = new LiberatorObject($object);

        $this->assertTrue(isset($proxy->foo));
        $this->assertEquals('bar', $proxy->foo);

        unset($proxy->foo);

        $this->assertFalse(isset($proxy->foo));

        $proxy->foo = 'baz';

        $this->assertTrue(isset($proxy->foo));
        $this->assertEquals('baz', $proxy->foo);
    }
}
