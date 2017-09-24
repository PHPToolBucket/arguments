# `arguments()`

Returns all the arguments to a function. Unlike `func_get_args()` it includes also the
arguments that were omitted.

## Composer Installation

```
composer require php-tool-bucket/arguments
```

## Simple example:

```php
function bar(int $a, int $b = 42){
    assert(
        func_get_args() === [555]
    );
    
    assert(
        arguments() === [555, 42]
    );
}

bar(555);
```

## Usage with closures (only solution currently):

```php
$c = function(int $foo, int $baz = 42) use(&$c){
    assert(
        mergeArguments($c, func_get_args()) === [555, 42]
    );
};

$c(555);

```

## Why?

Because delegation:

```php
<?php

use function PHPToolBucket\Bucket\arguments;

interface Foo
{
    function bar(int $foo = 1, int $bar = 2);
}

class FooClass implements Foo
{
    function bar(int $foo = 3, int $bar = 4){
        echo $foo . " " . $bar;
    }
}

class FooWrap implements Foo
{
    private $wrappee;
    function __construct(Foo $wrappee){
        $this->wrappee;    
    }
    
    function bar(int $foo = 5, int $bar = 6){
        echo "decorated";
        return $this->bar(...arguments());
        // Won't work properly with ...func_get_args().
    }
}

$fooClass = new FooClass();
$fooWrap = new FooWrap($fooClass);
$fooWrap->bar(7);
```
