<?php

require_once __DIR__ . '/../vendor/autoload.php';

$debug = false;

if ($debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../logs/development.log',
));

$app['debug'] = $debug;

$app->mount('/', new SilexApp\InitApp());

$app->run();

