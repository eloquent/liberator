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

use Eloquent\Pops\ProxyArray;

/**
 * An array proxy that circumvents access modifier restrictions.
 */
class LiberatorArray extends ProxyArray implements LiberatorProxyInterface
{
    /**
     * Get the proxy class.
     *
     * @return string The proxy class.
     */
    protected static function popsProxyClass()
    {
        return 'Eloquent\Liberator\Liberator';
    }
}
