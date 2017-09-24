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
