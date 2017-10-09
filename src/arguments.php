<?php declare(strict_types = 1);

namespace PHPToolBucket\Bucket;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function count;
use function substr;
use function debug_backtrace;
use ReflectionFunction;
use Closure;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Merges the arguments given to a function to the function's default arguments (if any).
 *
 * @param           Closure                                 $closure
 * `Closure` - The function defining the default arguments.
 *
 * @param           array                                   $givenArguments
 * `Array<Int, Mixed>` - The arguments provided at call time (i.e. `func_get_args()`).
 *
 * @return          array
 * `Array<Int, Mixed>` - The merged arguments.
 */
function mergeArguments(Closure $closure, array $givenArguments): array{
    $RF = new ReflectionFunction($closure);

    $RPs = $RF->getParameters();

    for($index = count($givenArguments); $index < count($RPs); $index++){
        if($RPs[$index]->isVariadic()){
            break;
        }
        $givenArguments[] = $RPs[$index]->getDefaultValue();
    }

    return $givenArguments;
}

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Returns the list of arguments that were used to call the current function or method.
 *
 * @throws
 *
 * @return          mixed[]
 * `array<int, mixed>` - Returns the list of arguments that were used to call the current
 * function or method.
 */
function arguments(): array{
    $trace = debug_backtrace();

    // [0] is this very function.

    if(isset($trace[1]) === FALSE){
        // @codeCoverageIgnoreStart
        // check argumentsTest.manual.php
        throw new Error("Cannot call `arguments()` from the global scope");
        // @codeCoverageIgnoreEnd
    }

    // Skip requires.
    $framePosition = 1;
    while(
        isset($trace[$framePosition]) &&
        isset($trace[$framePosition]["class"]) === FALSE && (
            $trace[$framePosition]["function"] === "require" ||
            $trace[$framePosition]["function"] === "include" ||
            $trace[$framePosition]["function"] === "require_once" ||
            $trace[$framePosition]["function"] === "include_once"
        )
    ){
        $framePosition++;
    }

    if(isset($trace[$framePosition]) === FALSE){
        // @codeCoverageIgnoreStart
        // check argumentsTest.manual.php
        throw new Error("Cannot call `arguments()` from the global scope");
        // @codeCoverageIgnoreEnd
    }

    $frame = $trace[$framePosition];

    if(substr($frame["function"], -9) === "{closure}"){
        throw new Error("Cannot call `arguments()` from a `Closure`");
    }

    if(
        isset($frame["class"]) &&
        isset($frame["object"]) &&
        $frame["type"] === "->"
    ){
        $closure = (function() use($frame){
            return Closure::fromCallable([$frame["object"], $frame["function"]]);
        })->call($frame["object"]);
    }elseif(
        isset($frame["class"]) &&
        $frame["type"] === "::"
    ){
        $closure = (function() use($frame){
            return Closure::fromCallable([$frame["class"], $frame["function"]]);
        })->bindTo(null, $frame["class"])->__invoke();
    }else{
        $closure = Closure::fromCallable($frame["function"]);
    }

    // @TODO this should probably be mergeArguments($RPs, $args), and would probably make
    // the above Closure::fromCallable a little less terrible
    return mergeArguments($closure, $frame["args"]);
}
