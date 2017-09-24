<?php declare(strict_types = 1); // atom

use function PHPToolBucket\Bucket\arguments;

require(__DIR__ . "/../vendor/autoload.php");

$verifyError = function($e){
    assert($e instanceof Error);
    assert($e->getMessage() === "Cannot call `arguments()` from the global scope");
};

try{
    arguments();
}catch(Throwable $e){
    $verifyError($e);
}

try{
    require(__DIR__ . "/files/55_66.php");
}catch(Throwable $e){
    $verifyError($e);
}

try{
    require(__DIR__ . "/files/to_55_66.php");
}catch(Throwable $e){
    $verifyError($e);
}

try{
    require(__DIR__ . "/files/to_to_55_66.php");
}catch(Throwable $e){
    $verifyError($e);
}

try{
    require(__DIR__ . "/files/to_to_to_55_66.php");
}catch(Throwable $e){
    $verifyError($e);
}
