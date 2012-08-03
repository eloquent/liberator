# Liberator

*A proxy for circumventing PHP access modifier restrictions.*

## Installation

Liberator requires PHP 5.3 or later.

### With [Composer](http://getcomposer.org/)

* Add 'eloquent/liberator' to your project's composer.json dependencies
* Run `php composer.phar install`

### Bare installation

* Clone from GitHub: `git clone git://github.com/eloquent/liberator.git`
* Use a [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
  compatible autoloader (namespace 'Eloquent\Liberator' in the 'src' directory)

## What is Liberator?

Liberator is an object proxy that allows you to access methods and properties
of an object or class that would normally be restricted by PHP. Essentially,
this means you ignore any 'private' or 'protected' keywords.

Liberator's primary use is as a testing tool. Unit tests can often be simplified
using a mix of partially mocked objects and Liberator.

Liberator is based upon the [Pops](https://github.com/eloquent/pops) object
proxy system.

## Usage

Liberator allows access to **protected** and **private** methods and properties
of objects as if they were marked **public**. It can do so for both objects and
classes (i.e. static methods and properties).

### For objects

Take the following class:

```php
<?php

class SeriousBusiness
{
    private function foo($adjective)
    {
        return 'foo is '.$adjective;
    }

    private $bar = 'mind';
}
```

Normally there is no way to call `foo()` or access `$bar` from outside the
`SeriousBusiness` class, but **Liberator** allows this to be achieved:

```php
<?php

use Eloquent\Liberator\Liberator;

$object = new SeriousBusiness;
$liberator = Liberator::liberate($object);

echo $liberator->foo('not so private...');   // outputs 'foo is not so private...'
echo $liberator->bar.' = blown';             // outputs 'mind = blown'
```

### For classes

The same concept applies for static methods and properties:

```php
<?php

class SeriousBusiness
{
    static private function baz($adjective)
    {
        return 'baz is '.$adjective;
    }

    static private $qux = 'mind';
}
```

To access these, a **class liberator** must be used instead of an
**object liberator**, but they operate in a similar manner:

```php
<?php

use Eloquent\Liberator\Liberator;

$liberator = Liberator::liberateClass('SeriousBusiness');

echo $liberator->baz('not so private...');   // outputs 'baz is not so private...'
echo $liberator->qux.' = blown';             // outputs 'mind = blown'
```

Alternatively, Liberator can generate a class that can be used statically:

```php
<?php

use Eloquent\Liberator\Liberator;

$liberatorClass = Liberator::liberateClassStatic('SeriousBusiness');

echo $liberatorClass::baz('not so private...');      // outputs 'baz is not so private...'
echo $liberatorClass::liberator()->qux.' = blown';   // outputs 'mind = blown'
```

Unfortunately, there is (currently) no __getStatic() or __setStatic() in PHP,
so accessing static properties in this way is a not as elegant as it could be.

## Applications for Liberator

* Writing [white-box](http://en.wikipedia.org/wiki/White-box_testing) style unit
  tests (testing protected/private methods).
* Modifying behaviour of poorly designed third-party libraries.

## Code quality

Liberator strives to attain a high level of quality. A full test suite is
available, and code coverage is closely monitored.

### Latest revision test suite results
[![Build Status](https://secure.travis-ci.org/eloquent/liberator.png)](http://travis-ci.org/eloquent/liberator)

### Latest revision test suite coverage
<http://ci.ezzatron.com/report/liberator/coverage/>
