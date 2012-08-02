<?php

$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Eloquent', __DIR__.'/src');

// include fixtures than cannot be autoloaded
$documentationFixturePath =
    __DIR__.'/src/Eloquent/Liberator/Test/Fixture/Documentation'
;
require $documentationFixturePath.'/SeriousBusiness.php';
