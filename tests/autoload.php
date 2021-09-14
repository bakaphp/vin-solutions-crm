<?php

/**
 * Setup autoloading.
 */
use function Baka\appPath;
use Dotenv\Dotenv;
use Phalcon\Loader;

require __DIR__ . '/PhalconUnitTestCase.php';

if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__DIR__) . '/');
}

//load classes
$loader = new Loader();
$loader->registerNamespaces([
    'Kanvas\VinSolutions' => appPath('src/'),
    'Kanvas\VinSolutions\Test' => appPath('tests/'),
    'Kanvas\VinSolutions\Test\Support' => appPath('tests/_support'),
]);

$loader->register();

require appPath('vendor/autoload.php');

$dotenv = Dotenv::createImmutable(appPath());
$dotenv->load();
