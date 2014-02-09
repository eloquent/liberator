# Liberator

*A proxy for circumventing PHP access modifier restrictions.*

[![The most recent stable version is 2.0.0][version-image]][Semantic versioning]
[![Current build status image][build-image]][Current build status]
[![Current coverage status image][coverage-image]][Current coverage status]

## Installation and documentation

- Available as [Composer] package [eloquent/liberator].
- [API documentation] available.

## What is Liberator?

*Liberator* allows access to **protected** and **private** methods and
properties of objects as if they were marked **public**. It can do so for both
objects and classes (i.e. static methods and properties).

*Liberator*'s primary use is as a testing tool, allowing direct access to
methods that would otherwise require complicated test harnesses or mocking to
test.

## Usage

### For objects

Take the following class:

```php
class SeriousBusiness
{
    private function foo($adjective)
    {
        return 'foo is ' . $adjective;
    }

    private $bar = 'mind';
}
```

Normally there is no way to call `foo()` or access `$bar` from outside the
`SeriousBusiness` class, but *Liberator* allows this to be achieved:

```php
use Eloquent\Liberator\Liberator;

$object = new SeriousBusiness;
$liberator = Liberator::liberate($object);

echo $liberator->foo('not so private...'); // outputs 'foo is not so private...'
echo $liberator->bar . ' = blown';         // outputs 'mind = blown'
```

### For classes

The same concept applies for static methods and properties:

```php
class SeriousBusiness
{
    static private function baz($adjective)
    {
        return 'baz is ' . $adjective;
    }

    static private $qux = 'mind';
}
```

To access these, a *class liberator* must be used instead of an *object
liberator*, but they operate in a similar manner:

```php
use Eloquent\Liberator\Liberator;

$liberator = Liberator::liberateClass('SeriousBusiness');

echo $liberator->baz('not so private...'); // outputs 'baz is not so private...'
echo $liberator->qux . ' = blown';         // outputs 'mind = blown'
```

Alternatively, *Liberator* can generate a class that can be used statically:

```php
use Eloquent\Liberator\Liberator;

$liberatorClass = Liberator::liberateClassStatic('SeriousBusiness');

echo $liberatorClass::baz('not so private...');      // outputs 'baz is not so private...'
echo $liberatorClass::liberator()->qux . ' = blown'; // outputs 'mind = blown'
```

Unfortunately, there is (currently) no __getStatic() or __setStatic() in PHP,
so accessing static properties in this way is a not as elegant as it could be.

## Applications for Liberator

- Writing [white-box] style unit tests (testing protected/private methods).
- Modifying behavior of poorly designed third-party libraries.

<!-- References -->

[white-box]: http://en.wikipedia.org/wiki/White-box_testing

[API documentation]: http://lqnt.co/liberator/artifacts/documentation/api/
[Composer]: http://getcomposer.org/
[build-image]: http://img.shields.io/travis/eloquent/liberator/develop.svg "Current build status for the develop branch"
[Current build status]: https://travis-ci.org/eloquent/liberator
[coverage-image]: http://img.shields.io/coveralls/eloquent/liberator/develop.svg "Current test coverage for the develop branch"
[Current coverage status]: https://coveralls.io/r/eloquent/liberator
[eloquent/liberator]: https://packagist.org/packages/eloquent/liberator
[Semantic versioning]: http://semver.org/
[version-image]: http://img.shields.io/:semver-2.0.0-brightgreen.svg "This project uses semantic versioning"
