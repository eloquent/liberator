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
use PHPUnit_Framework_TestCase;

class LiberatorArrayTest extends PHPUnit_Framework_TestCase
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

        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy['object']);
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy['object']->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy['object']->arrayValue());
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy['object']->string());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy['array']);
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy['array']['object']);
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorObject', $recursiveProxy['array']['object']->object());
        $this->assertInstanceOf('Eloquent\Liberator\LiberatorArray', $recursiveProxy['array']['array']);
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy['array']['string']);
        $this->assertInstanceOf('Eloquent\Pops\ProxyPrimitive', $recursiveProxy['string']);
    }
}
