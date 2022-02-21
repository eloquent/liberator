# Liberator

*A proxy for circumventing PHP access modifier restrictions.*

## Installation and documentation

- Available as [Composer] package [eloquent/liberator].

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

[composer]: http://getcomposer.org/
[eloquent/liberator]: https://packagist.org/packages/eloquent/liberator
