<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Liberator;

use Eloquent\Liberator\Test\Fixture\Object;
use Eloquent\Liberator\Test\TestCase;

class LiberatorArrayTest extends TestCase
{
    public function testRecursive()
    {
        $array = array(
            'object' => new Object,
            'array' => array(
                'object' => new Object,
                'array' => array(),
                'string' => 'string',
             ),
            'string' => 'string',
        );
        $recursiveProxy = new LiberatorArray($array, true);

        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $recursiveProxy['object']
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $recursiveProxy['object']->object()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $recursiveProxy['object']->arrayValue()
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $recursiveProxy['object']->string()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $recursiveProxy['array']
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $recursiveProxy['array']['object']
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorObject',
            $recursiveProxy['array']['object']->object()
        );
        $this->assertInstanceOf(
            __NAMESPACE__.'\LiberatorArray',
            $recursiveProxy['array']['array']
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $recursiveProxy['array']['string']
        );
        $this->assertInstanceOf(
            'Eloquent\Pops\ProxyPrimitive',
            $recursiveProxy['string']
        );
    }
}
