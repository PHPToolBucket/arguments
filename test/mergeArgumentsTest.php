<?php declare(strict_types = 1);

namespace PHPToolBucket\Bucket\Tests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function PHPToolBucket\Bucket\mergeArguments;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests for {@see mergeArguments()}.
 */
class mergeArgumentsTest extends TestCase
{
    function test_that_default_is_added(){
        $closure = function(int $a = 55){};
        $result = mergeArguments($closure, []);
        self::assertSame([55], $result);
    }

    function test_that_given_replaces_default(){
        $closure = function(int $a = 55){};
        $result = mergeArguments($closure, [77]);
        self::assertSame([77], $result);
    }

    function test_that_multiple_defaults_are_added(){
        $closure = function(int $a = 55, int $b = 66, int $c = 77){};
        $result = mergeArguments($closure, []);
        self::assertSame([55, 66, 77], $result);
    }

    function test_that_multiple_given_replace_defaults(){
        $closure = function(int $a = 55, int $b = 66, int $c = 77){};
        $result = mergeArguments($closure, [11, 22, 33]);
        self::assertSame([11, 22, 33], $result);
    }

    function test_multiple_mix(){
        $closure = function(int $a = 55, int $b = 66, int $c = 77, int $d = 88){};
        $result = mergeArguments($closure, [11, 22]);
        self::assertSame([11, 22, 77, 88], $result);
    }

    function test_variadic(){
        $closure = function($a, int ...$b){};
        $result = mergeArguments($closure, [11, 22, 33]);
        self::assertSame([11, 22, 33], $result);

        $result = mergeArguments($closure, [11, 22]);
        self::assertSame([11, 22], $result);

        $result = mergeArguments($closure, [11]);
        self::assertSame([11], $result);
    }
}
