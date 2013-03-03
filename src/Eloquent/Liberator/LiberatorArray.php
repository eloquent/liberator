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

use Eloquent\Pops\ProxyArray;

class LiberatorArray extends ProxyArray
{
    /**
     * @return string
     */
    protected static function popsProxyClass()
    {
        return __NAMESPACE__.'\Liberator';
    }
}
