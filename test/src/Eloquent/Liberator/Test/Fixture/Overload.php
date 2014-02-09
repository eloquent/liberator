<?php

/*
 * This file is part of the Liberator package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Liberator\Test\Fixture;

class Overload extends Object
{
    public function __set($property, $value)
    {
        $this->values[$property] = $value;
    }

    public function __get($property)
    {
        return $this->values[$property];
    }

    public function __isset($property)
    {
        return array_key_exists($property, $this->values);
    }

    public function __unset($property)
    {
        unset($this->values[$property]);
    }

    public $values = array();
}
