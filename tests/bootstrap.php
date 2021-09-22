<?php
if (!defined('MODULE_ROOT_DIR')) {
    define('MODULE_ROOT_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_ROOT_DIR')) {
    define('APPLICATION_ROOT_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_VENDOR_DIR')) {
    define('APPLICATION_VENDOR_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR);
}

if (!defined('APPLICATION_STORE')) {
    define('APPLICATION_STORE', 'DE');
}

if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'dev');
}

// Copy config files
$configSharedTargetDirectory = APPLICATION_ROOT_DIR . 'config' . DIRECTORY_SEPARATOR . 'Shared'  . DIRECTORY_SEPARATOR;
if (!is_dir($configSharedTargetDirectory)) {
    mkdir($configSharedTargetDirectory, 0777, true);
}

$configZedTargetDirectory = APPLICATION_ROOT_DIR . 'config' . DIRECTORY_SEPARATOR . 'Zed'  . DIRECTORY_SEPARATOR;
if (!is_dir($configZedTargetDirectory)) {
    mkdir($configZedTargetDirectory, 0777, true);
}

$configSourceDirectory = MODULE_ROOT_DIR . 'ci' . DIRECTORY_SEPARATOR;
copy($configSourceDirectory . 'config_default.php', $configSharedTargetDirectory . 'config_default.php');
copy($configSourceDirectory . 'default_store.php', $configSharedTargetDirectory . 'default_store.php');

copy($configSourceDirectory . 'stores.php', $configSharedTargetDirectory . 'stores.php');
copy(MODULE_ROOT_DIR . 'src/SprykerSdk/Zed/SprykGui/Communication/navigation.xml', $configZedTargetDirectory . 'navigation.xml');

$config = \Spryker\Shared\Config\Config::getInstance();
$config->init();
