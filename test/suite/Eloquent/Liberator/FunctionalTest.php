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
        $liberator = Liberator::liberate($object);

        $this->assertEquals(
            'foo is not so private...',
            $liberator->foo('not so private...')
        );
        $this->assertEquals(
            'mind = blown',
            $liberator->bar.' = blown'
        );
    }

    public function testDocumentationLiberatorClass()
    {
        $liberator = Liberator::liberateClass('SeriousBusiness');

        $this->assertEquals(
            'baz is not so private...',
            $liberator->baz('not so private...')
        );
        $this->assertEquals(
            'mind = blown',
            $liberator->qux.' = blown'
        );
    }

    public function testDocumentationLiberatorClassStatic()
    {
        $liberatorClass = Liberator::liberateClassStatic('SeriousBusiness');

        $this->assertEquals(
            'baz is not so private...',
            $liberatorClass::baz('not so private...')
        );
        $this->assertEquals(
            'mind = blown',
            $liberatorClass::liberator()->qux.' = blown'
        );
    }
}
