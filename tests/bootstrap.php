<?php

define('APPLICATION_ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('APPLICATION_STORE', 'DE');
define('PROJECT_NAMESPACES', 'Pyz');

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/codeception/codeception/autoload.php');


spl_autoload_register(function ($className) {
    if (strrpos($className, 'Transfer') === false) {
        return false;
    }

    $classNameParts = explode('\\', $className);

    $transferFileName = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
    $transferFilePath = APPLICATION_ROOT_DIR . 'src' . DIRECTORY_SEPARATOR . $transferFileName;

    if (!file_exists($transferFilePath)) {
        return false;
    }

    require_once $transferFilePath;

    return true;
});

