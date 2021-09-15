<?php

if (!defined('APPLICATION_ROOT_DIR')) {
    define('APPLICATION_ROOT_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_VENDOR_DIR')) {
    define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . 'vendor' . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_STORE')) {
    define('APPLICATION_STORE', 'DE');
}

if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'dev');
}

// Copy config files
$configTargetDirectory = APPLICATION_ROOT_DIR . 'config' . DIRECTORY_SEPARATOR . 'Shared'  . DIRECTORY_SEPARATOR;
if (!is_dir($configTargetDirectory)) {
    mkdir($configTargetDirectory, 0777, true);
}

$configSourceDirectory = APPLICATION_ROOT_DIR . 'ci' . DIRECTORY_SEPARATOR;
copy($configSourceDirectory . 'config_local.php', $configTargetDirectory . 'config_local.php');
copy($configSourceDirectory . 'stores.php', $configTargetDirectory . 'stores.php');

$config = \Spryker\Shared\Config\Config::getInstance();
$config->init();
