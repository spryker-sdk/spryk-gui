<?php

if (!defined('APPLICATION_ROOT_DIR')) {
    define('APPLICATION_ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'app/');
}
if (!defined('APPLICATION_ROOT_DIR')) {
    define(' APPLICATION_VENDOR_DIR',  __DIR__  . '/../vendor');
}

if (!defined('APPLICATION_STORE')) {
    define('APPLICATION_STORE', 'DE');
}
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'dev');
}

$config = \Spryker\Shared\Config\Config::getInstance();
$config->init();
