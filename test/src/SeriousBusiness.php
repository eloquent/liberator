<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

class SeriousBusiness
{
    private static function baz($adjective)
    {
        return 'baz is ' . $adjective;
    }

    private function foo($adjective)
    {
        return 'foo is ' . $adjective;
    }

    private static $qux = 'mind';
    private $bar = 'mind';
}
