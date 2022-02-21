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

use Eloquent\Liberator\Test\Fixture\Obj;
use Eloquent\Pops\ProxyPrimitive;
use PHPUnit\Framework\TestCase;

class LiberatorTest extends TestCase
{
    public function testLiberator()
    {
        $expected = new LiberatorClass('Eloquent\Liberator\Test\Fixture\Obj');

        $this->assertEquals($expected, Liberator::liberateClass('Eloquent\Liberator\Test\Fixture\Obj'));

        $class = Liberator::liberateClassStatic('Eloquent\Liberator\Test\Fixture\Obj');

        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(is_subclass_of($class, 'Eloquent\Liberator\LiberatorClass'));

        $expected = new LiberatorArray([]);

        $this->assertEquals($expected, Liberator::liberate([]));

        $expected = new LiberatorObject(new Obj());

        $this->assertEquals($expected, Liberator::liberate(new Obj()));

        $expected = new ProxyPrimitive('string');

        $this->assertEquals($expected, Liberator::liberate('string'));
    }
}
