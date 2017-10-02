<?php declare(strict_types = 1);

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function PHPToolBucket\Bucket\arguments;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

$result = arguments();
TestCase::assertSame([55, 66], $result);


