<?php
require_once __DIR__ . '/autoloader.php';

ini_set('max_execution_time', 50);

$app = new App\App();
$app->run();
