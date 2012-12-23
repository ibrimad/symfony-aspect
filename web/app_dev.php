<?php

use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
//if (isset($_SERVER['HTTP_CLIENT_IP'])
//    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
//    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
//) {
//    header('HTTP/1.0 403 Forbidden');
//    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
//}

include __DIR__.'/../vendor/lisachenko/go-aop-php/src/Go/Core/AspectKernel.php';
include __DIR__.'/../app/DemoAspectKernel.php';

// Prevent an error about nesting level
ini_set('xdebug.max_nesting_level', 300);

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
    'debug' => true,
));
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
