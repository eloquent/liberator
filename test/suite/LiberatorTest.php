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
use Eloquent\Pops\ProxyPrimitive;
use PHPUnit_Framework_TestCase;

class LiberatorTest extends PHPUnit_Framework_TestCase
{
    public function testLiberator()
    {
        $expected = new LiberatorClass('Eloquent\Liberator\Test\Fixture\Object');

        $this->assertEquals($expected, Liberator::liberateClass('Eloquent\Liberator\Test\Fixture\Object'));

        $class = Liberator::liberateClassStatic('Eloquent\Liberator\Test\Fixture\Object');

        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(is_subclass_of($class, 'Eloquent\Liberator\LiberatorClass'));

        $expected = new LiberatorArray(array());

        $this->assertEquals($expected, Liberator::liberate(array()));

        $expected = new LiberatorObject(new Object);

        $this->assertEquals($expected, Liberator::liberate(new Object));

        $expected = new ProxyPrimitive('string');

        $this->assertEquals($expected, Liberator::liberate('string'));
    }
}
