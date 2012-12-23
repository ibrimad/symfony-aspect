<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

//$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

include __DIR__.'/../vendor/lisachenko/go-aop-php/src/Go/Core/AspectKernel.php';
include __DIR__.'/../app/DemoAspectKernel.php';

// Initialize aspect container
$aspectKernel = DemoAspectKernel::getInstance();
$aspectKernel->init(array(
    'autoload' => array(
        'Aspect'           => __DIR__.'/../src/Aspect',
        'Go'               => __DIR__.'/../vendor/lisachenko/go-aop-php/src/',
        'TokenReflection'  => __DIR__.'/../vendor/andrewsville/php-token-reflection/',
        'Doctrine\\Common' => __DIR__.'/../vendor/doctrine/common/lib/'
    ),
    'includePaths' => array(
        __DIR__.'/../src/',

    ),
    'cacheDir' => __DIR__ .'/../app/cache/aspect',
    'appDir'   => __DIR__.'/../',
    'debug' => false,
));

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
*/

require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
