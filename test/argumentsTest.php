<?php declare(strict_types = 1);

namespace PHPToolBucket\Bucket\Tests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function PHPToolBucket\Bucket\arguments;
use PHPUnit\Framework\TestCase;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class FromMethod
{
    function method(int $foo, int $bar = 66){
        $result = arguments();
        TestCase::assertSame([55, 66], $result);
        require(__DIR__ . "/files/55_66.php");
        require(__DIR__ . "/files/to_55_66.php");
        require(__DIR__ . "/files/to_to_55_66.php");
    }

    private function privateMethod(int $foo, int $bar = 66){
        $result = arguments();
        TestCase::assertSame([55, 66], $result);
        require(__DIR__ . "/files/55_66.php");
        require(__DIR__ . "/files/to_55_66.php");
        require(__DIR__ . "/files/to_to_55_66.php");
    }

    static function staticMethod(int $foo, int $bar = 66){
        $result = arguments();
        TestCase::assertSame([55, 66], $result);
        require(__DIR__ . "/files/55_66.php");
        require(__DIR__ . "/files/to_55_66.php");
        require(__DIR__ . "/files/to_to_55_66.php");
    }

    private static function privateStaticMethod(int $foo, int $bar = 66){
        $result = arguments();
        TestCase::assertSame([55, 66], $result);
        require(__DIR__ . "/files/55_66.php");
        require(__DIR__ . "/files/to_55_66.php");
        require(__DIR__ . "/files/to_to_55_66.php");
    }
}

function fromFunction(int $foo, int $bar = 66){
    $result = arguments();
    TestCase::assertSame([55, 66], $result);
    require(__DIR__ . "/files/55_66.php");
    require(__DIR__ . "/files/to_55_66.php");
    require(__DIR__ . "/files/to_to_55_66.php");
}

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests for {@see arguments()}.
 */
class argumentsTest extends TestCase
{
    function test(){
        $o = new FromMethod();
        $o->method(55, 66);
        $o->method(55);
        (function(){ $this->privateMethod(55, 66); })->call($o);
        (function(){ $this->privateMethod(55); })->call($o);

        FromMethod::staticMethod(55, 66);
        FromMethod::staticMethod(55);
        (function(){ FromMethod::privateStaticMethod(55, 66); })->call($o);
        (function(){ FromMethod::privateStaticMethod(55); })->call($o);

        fromFunction(55, 66);
        fromFunction(55);
    }

    /**
     * @expectedException Error
     * @expectedExceptionMessage Cannot call `arguments()` from a `Closure`
     */
    function test_that_throws_when_used_in_a_closure(){
        (function(){ arguments(); })();
    }
}
