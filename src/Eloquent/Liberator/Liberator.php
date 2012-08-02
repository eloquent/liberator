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

use Eloquent\Pops\Pops;

class Liberator extends Pops
{
    /**
     * @return string
     */
    protected static function proxyArrayClass()
    {
        return __NAMESPACE__.'\LiberatorArray';
    }

    /**
     * @return string
     */
    protected static function proxyClassClass()
    {
        return __NAMESPACE__.'\LiberatorClass';
    }

    /**
     * @return string
     */
    protected static function proxyObjectClass()
    {
        return __NAMESPACE__.'\LiberatorObject';
    }
}
