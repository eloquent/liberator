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
use Eloquent\Pops\ProxyPrimitive;

class LiberatorTest extends TestCase
{
    public function testLiberator()
    {
        $expected = new LiberatorClass(__NAMESPACE__.'\Test\Fixture\Object');

        $this->assertEquals($expected, Liberator::liberateClass(
            __NAMESPACE__.'\Test\Fixture\Object'
        ));


        $class = Liberator::liberateClassStatic(
            __NAMESPACE__.'\Test\Fixture\Object'
        );

        $this->assertTrue(class_exists($class));
        $this->assertTrue(
            is_subclass_of($class, __NAMESPACE__.'\LiberatorClass')
        );


        $expected = new LiberatorArray(array());

        $this->assertEquals($expected, Liberator::liberate(array()));


        $expected = new LiberatorObject(new Object);

        $this->assertEquals($expected, Liberator::liberate(new Object));


        $expected = new ProxyPrimitive('string');

        $this->assertEquals($expected, Liberator::liberate('string'));
    }
}
