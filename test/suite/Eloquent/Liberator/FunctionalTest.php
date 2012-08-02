<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Eloquent\Liberator\Liberator;
use Eloquent\Liberator\Test\TestCase;

class FunctionalTest extends TestCase
{
    public function testDocumentationLiberatorObject()
    {
        $object = new SeriousBusiness;
        $proxy = Liberator::proxy($object);

        $this->assertEquals(
            'foo is not so private...',
            $proxy->foo('not so private...')
        );
        $this->assertEquals(
            'mind = blown',
            $proxy->bar.' = blown'
        );
    }

    public function testDocumentationLiberatorClass()
    {
        $proxy = Liberator::proxyClass('SeriousBusiness');

        $this->assertEquals(
            'baz is not so private...',
            $proxy->baz('not so private...')
        );
        $this->assertEquals(
            'mind = blown',
            $proxy->qux.' = blown'
        );
    }

    public function testDocumentationLiberatorClassStatic()
    {
        $proxyClass = Liberator::proxyClassStatic('SeriousBusiness');

        $this->assertEquals(
            'baz is not so private...',
            $proxyClass::baz('not so private...')
        );
        $this->assertEquals(
            'mind = blown',
            $proxyClass::popsProxy()->qux.' = blown'
        );
    }
}
