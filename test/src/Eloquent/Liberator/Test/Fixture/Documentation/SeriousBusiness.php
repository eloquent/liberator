<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class SeriousBusiness
{
    static private function baz($adjective)
    {
        return 'baz is '.$adjective;
    }

    private function foo($adjective)
    {
        return 'foo is '.$adjective;
    }

    static private $qux = 'mind';
    private $bar = 'mind';
}
