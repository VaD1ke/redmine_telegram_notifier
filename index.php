<?php
require_once __DIR__ . '/autoloader.php';

ini_set('max_execution_time', 50);

$di = new Zend\Di\Di();
$app = new App\App($di);
$app->run();
