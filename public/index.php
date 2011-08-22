<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

/* -- Define App Environment -- */
if (strstr($_SERVER['SERVER_NAME'],'dev.yipl.net'))
    define('APPLICATION_ENV', 'staging');
else if (strstr($_SERVER['SERVER_NAME'],'dev') )
    define('APPLICATION_ENV', 'development');
 
else if (strstr($_SERVER['SERVER_NAME'],'demo'))
    define('APPLICATION_ENV', 'demo');
else
    define('APPLICATION_ENV', 'production');
    
    
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();