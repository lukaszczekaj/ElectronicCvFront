<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
//define('APPLICATION_ENV', 'production'); // szybka podmianka w tryb produkcyjny

define('SITE_URL', filter_input(INPUT_SERVER, "SERVER_NAME"));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Composer autoloader */
if (file_exists(realpath(APPLICATION_PATH . '/../vendor/autoload.php'))) {
    require_once realpath(APPLICATION_PATH . '/../vendor/autoload.php');
}

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
        ->run();