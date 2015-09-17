<?php
require_once __DIR__ . '/vendor/autoload.php';
$loader = new \Zend\Loader\StandardAutoloader;
$loader->registerNamespace(
    'App',
    __DIR__ . '/App'
);
$loader->register();