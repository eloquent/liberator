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

use Eloquent\Liberator\Test\TestCase;

class LiberatorClassTest extends TestCase
{
    protected function setUp(): void
    {
        $this->class = 'Eloquent\Liberator\Test\Fixture\Obj';
        $this->proxy = new LiberatorClass($this->class);
    }

    public function fixtureData()
    {
        $data = [];

        $class = 'Eloquent\Liberator\Test\Fixture\Obj';
        $proxy = new LiberatorClass($class);
        $data['Class with no inheritance'] = [$class, $proxy];

        $class = 'Eloquent\Liberator\Test\Fixture\ChildObject';
        $proxy = new LiberatorClass($class);
        $data['Child class'] = [$class, $proxy];

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
        $this->assertLiberatorCall($proxy, 'staticPublicMethod', ['foo', 'bar']);
        $this->assertLiberatorCall($proxy, 'staticProtectedMethod', ['foo', 'bar']);
        $this->assertLiberatorCall($proxy, 'staticPrivateMethod', ['foo', 'bar']);
        $this->assertLiberatorCall($proxy, 'foo', ['bar', 'baz'], true);
    }

    public function testCallByReference()
    {
        $variable = null;
        $arguments = [&$variable, 'foo'];
        $this->proxy->liberatorCall('staticByReference', $arguments);

        $this->assertSame('foo', $variable);
    }

    public function testCallByReferenceStatic()
    {
        $class = LiberatorClass::popsGenerateStaticClassProxy('Eloquent\Liberator\Test\Fixture\Obj');
        $variable = null;
        $arguments = [&$variable, 'foo'];
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
        return [
            ['__set', ['foo', 'bar']],
            ['__get', ['foo']],
            ['__unset', ['foo']],
        ];
    }

    /**
     * @dataProvider setGetFailureData
     */
    public function testSetGetFailure($method, array $arguments)
    {
        $this->expectException('LogicException');
        $this->expectExceptionMessage('Access to undeclared static property: Eloquent\Liberator\Test\Fixture\Obj::$' . $arguments[0]);
        call_user_func_array([$this->proxy, $method], $arguments);
    }

    public function testPopsGenerateStaticClassProxy()
    {
        $class = LiberatorClass::popsGenerateStaticClassProxy('Eloquent\Liberator\Test\Fixture\Obj');

        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(is_subclass_of($class, 'Eloquent\Liberator\LiberatorClass'));

        $expected = new $class('Eloquent\Liberator\Test\Fixture\Obj');
        $proxy = $class::liberator();

        $this->assertEquals($expected, $proxy);

        // recursive tests
        $class = LiberatorClass::popsGenerateStaticClassProxy('Eloquent\Liberator\Test\Fixture\Obj', true);

        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $class::staticObject());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $class::staticObject()->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $class::staticObject()->arrayValue());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $class::staticObject()->string());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $class::staticArray());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $class::staticString());
    }
}
